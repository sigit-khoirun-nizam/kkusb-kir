<?php

use App\Models\User;
use App\Models\Kendaraan;
use App\Models\KirHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it strips dots from biaya and jasa in KirController prosesStore', function () {
    $user = User::factory()->create();
    $kendaraan = Kendaraan::create([
        'nomor_pintu' => 'P123',
        'nopol' => 'B 1234 CD',
        'jenis' => 'Truck',
        'status' => 'aktif',
    ]);

    $file = \Illuminate\Http\UploadedFile::fake()->create('receipt.pdf', 100);

    $response = $this->actingAs($user)->post(route('kir.proses-store', $kendaraan), [
        'tanggal_proses' => '2026-06-19',
        'exp_kir_baru' => '2026-12-19',
        'biaya' => '150.000',
        'jasa' => '50.000',
        'no_pr' => 'PR-123',
        'no_spk' => 'SPK-123',
        'dokumen' => $file,
    ]);

    $response->assertRedirect(route('kir.monitoring'));
    
    // Assert database values
    $this->assertDatabaseHas('kendaraan', [
        'id' => $kendaraan->id,
        'biaya' => 150000,
        'jasa' => 50000,
        'total' => 200000,
    ]);

    $this->assertDatabaseHas('kir_histories', [
        'kendaraan_id' => $kendaraan->id,
        'biaya' => 150000,
        'jasa' => 50000,
        'total' => 200000,
    ]);

    $history = KirHistory::first();
    $this->assertDatabaseHas('kir_documents', [
        'kendaraan_id' => $kendaraan->id,
        'kir_history_id' => $history->id,
        'nama_file' => 'receipt.pdf',
    ]);
});

test('it strips dots from biaya and jasa in KendaraanController store and update', function () {
    $user = User::factory()->create();

    // 1. Test store
    $response = $this->actingAs($user)->post(route('kendaraan.store'), [
        'nomor_pintu' => 'P456',
        'nopol' => 'B 4567 EF',
        'jenis' => 'Mixer',
        'deskripsi' => 'New vehicle',
        'status' => 'aktif',
        'biaya' => '120.000',
        'jasa' => '40.000',
    ]);

    $response->assertRedirect(route('kendaraan.index'));

    $this->assertDatabaseHas('kendaraan', [
        'nopol' => 'B 4567 EF',
        'biaya' => 120000,
        'jasa' => 40000,
        'total' => 160000,
    ]);

    $kendaraan = Kendaraan::where('nopol', 'B 4567 EF')->first();

    // 2. Test update
    $response = $this->actingAs($user)->put(route('kendaraan.update', $kendaraan), [
        'nomor_pintu' => 'P456-edited',
        'nopol' => 'B 4567 EF',
        'jenis' => 'Mixer',
        'deskripsi' => 'Updated vehicle',
        'status' => 'aktif',
        'biaya' => '250.000',
        'jasa' => '75.000',
    ]);

    $response->assertRedirect(route('kendaraan.index'));

    $this->assertDatabaseHas('kendaraan', [
        'id' => $kendaraan->id,
        'nomor_pintu' => 'P456-edited',
        'biaya' => 250000,
        'jasa' => 75000,
        'total' => 325000,
    ]);
});

test('it preserves query parameters in pagination links', function () {
    $user = User::factory()->create();
    
    // Create 15 vehicles to trigger pagination (since paginate is 10)
    for ($i = 1; $i <= 15; $i++) {
        Kendaraan::create([
            'nomor_pintu' => 'P' . $i,
            'nopol' => 'B ' . $i . ' AB',
            'jenis' => 'Truck',
            'status' => 'aktif',
            // Set expiration date to > 60 days from now to match 'aman' status
            'exp_kir' => now()->addDays(70),
        ]);
    }

    $response = $this->actingAs($user)->get(route('kir.monitoring', [
        'status' => 'aman',
        'search' => 'P',
    ]));

    $response->assertStatus(200);
    
    // Assert that the page 2 pagination link contains status=aman and search=P
    $response->assertSee('status=aman');
    $response->assertSee('search=P');
    $response->assertSee('page=2');
});

test('it renders the print history page', function () {
    $user = User::factory()->create();
    $kendaraan = Kendaraan::create([
        'nomor_pintu' => 'P123',
        'nopol' => 'B 1234 CD',
        'jenis' => 'Truck',
        'status' => 'aktif',
    ]);
    $history = KirHistory::create([
        'kendaraan_id' => $kendaraan->id,
        'exp_kir_lama' => null,
        'exp_kir_baru' => '2026-12-19',
        'biaya' => 150000,
        'jasa' => 50000,
        'total' => 200000,
        'tanggal_proses' => '2026-06-19',
    ]);

    $response = $this->actingAs($user)->get(route('kir.history.print', $history));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
    $this->assertStringStartsWith('%PDF-', $response->getContent());
});

test('it falls back to vehicle document in history page', function () {
    $user = User::factory()->create();
    $kendaraan = Kendaraan::create([
        'nomor_pintu' => 'P999',
        'nopol' => 'B 9999 XY',
        'jenis' => 'Truck',
        'status' => 'aktif',
    ]);
    
    // Create vehicle document (without kir_history_id to simulate legacy data)
    \App\Models\KirDocument::create([
        'kendaraan_id' => $kendaraan->id,
        'nama_file' => 'legacy_receipt.pdf',
        'path' => 'kir_documents/legacy.pdf',
    ]);

    $history = KirHistory::create([
        'kendaraan_id' => $kendaraan->id,
        'exp_kir_lama' => null,
        'exp_kir_baru' => '2026-12-19',
        'biaya' => 150000,
        'jasa' => 50000,
        'total' => 200000,
        'tanggal_proses' => '2026-06-19',
    ]);

    $response = $this->actingAs($user)->get(route('kir.history'));

    $response->assertStatus(200);
    $response->assertSee('Buka PDF');
    $response->assertSee('legacy.pdf');
});

test('it processes and stores optional additional fees in KirController prosesStore', function () {
    $user = User::factory()->create();
    $kendaraan = Kendaraan::create([
        'nomor_pintu' => 'P123',
        'nopol' => 'B 1234 CD',
        'jenis' => 'Truck',
        'status' => 'aktif',
    ]);

    // Create fee types
    $feeType1 = \App\Models\AdditionalFeeType::create(['name' => 'TIDAK SESUAI REAL FISIK', 'status' => 'aktif']);
    $feeType2 = \App\Models\AdditionalFeeType::create(['name' => 'STIKER PEMANTUL', 'status' => 'aktif']);

    $response = $this->actingAs($user)->post(route('kir.proses-store', $kendaraan), [
        'tanggal_proses' => '2026-06-19',
        'exp_kir_baru' => '2026-12-19',
        'biaya' => '150.000',
        'jasa' => '50.000',
        'no_pr' => 'PR-123',
        'no_spk' => 'SPK-123',
        'additional_fees' => [
            ['type_id' => $feeType1->id, 'amount' => '25.000'],
            ['type_id' => $feeType2->id, 'amount' => '15.000'],
        ]
    ]);

    $response->assertRedirect(route('kir.monitoring'));

    // Assert total includes additional fees: 150000 + 50000 + 25000 + 15000 = 240000
    $this->assertDatabaseHas('kendaraan', [
        'id' => $kendaraan->id,
        'biaya' => 150000,
        'jasa' => 50000,
        'total' => 240000,
    ]);

    $this->assertDatabaseHas('kir_histories', [
        'kendaraan_id' => $kendaraan->id,
        'biaya' => 150000,
        'jasa' => 50000,
        'total' => 240000,
    ]);

    $history = KirHistory::first();
    $this->assertDatabaseHas('kir_history_additional_fees', [
        'kir_history_id' => $history->id,
        'additional_fee_type_id' => $feeType1->id,
        'amount' => 25000,
    ]);

    $this->assertDatabaseHas('kir_history_additional_fees', [
        'kir_history_id' => $history->id,
        'additional_fee_type_id' => $feeType2->id,
        'amount' => 15000,
    ]);
});

test('it renders the report export page', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('report.export'));
    
    $response->assertStatus(200);
    $response->assertSee('Export Laporan KIR');
    $response->assertSee('Export to Excel');
    $response->assertSee('Export to PDF');
});

test('it exports KIR history to Excel filtered by dates', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('report.export', [
        'format' => 'excel',
        'start_date' => '2026-06-01',
        'end_date' => '2026-06-30',
    ]));
    
    $response->assertStatus(200);
    $response->assertHeader('Content-Disposition', 'attachment; filename=laporan_histori_kir_' . now()->format('Ymd') . '.xlsx');
});

test('it exports KIR history to PDF filtered by dates', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('report.export', [
        'format' => 'pdf',
        'start_date' => '2026-06-01',
        'end_date' => '2026-06-30',
    ]));
    
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
    $this->assertStringStartsWith('%PDF-', $response->getContent());
});

test('it passes chart data to dashboard view', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get(route('dashboard'));
    
    $response->assertStatus(200);
    $response->assertViewHas('chartData');
    
    $chartData = $response->viewData('chartData');
    $this->assertCount(12, $chartData);
});

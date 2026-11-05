<?php

namespace Tests\Feature;

use App\Models\Documento;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentoArchivoTest extends TestCase
{
    public function test_attachment_is_resolved_on_public_disk_even_when_default_is_local(): void
    {
        config(['filesystems.default' => 'local']);
        Storage::fake('local');
        Storage::fake('public');
        Storage::disk('public')->put('documentos/example.pdf', '%PDF-1.4');

        $documento = new Documento(['archivo_ruta' => 'documentos/example.pdf']);

        $this->assertTrue($documento->tiene_archivo);
        $this->assertSame(Storage::disk('public')->url('documentos/example.pdf'), $documento->archivo_url);
    }

    public function test_missing_or_empty_attachment_has_no_url(): void
    {
        Storage::fake('public');
        $documento = new Documento(['archivo_ruta' => 'documentos/missing.pdf']);
        $this->assertFalse($documento->tiene_archivo);
        $this->assertNull($documento->archivo_url);
        $documento->archivo_ruta = null;
        $this->assertFalse($documento->tiene_archivo);
    }
}

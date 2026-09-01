<?php

namespace App\Guides\Stubs;

use App\Guides\ProblemFacts;

class LaravelStubs
{
    public static function migration(ProblemFacts $facts): string
    {
        return implode("\n", [
            '<?php',
            '',
            'use Illuminate\Database\Migrations\Migration;',
            'use Illuminate\Database\Schema\Blueprint;',
            'use Illuminate\Support\Facades\Schema;',
            '',
            'return new class extends Migration',
            '{',
            '    /**',
            '     * Run the migrations.',
            '     */',
            '    public function up(): void',
            '    {',
            "        Schema::create('".$facts->table."', function (Blueprint \$table) {",
            '            $table->id();',
            '            $table->timestamps();',
            '        });',
            '    }',
            '',
            '    /**',
            '     * Reverse the migrations.',
            '     */',
            '    public function down(): void',
            '    {',
            "        Schema::dropIfExists('".$facts->table."');",
            '    }',
            '};',
        ]);
    }

    public static function model(ProblemFacts $facts): string
    {
        return implode("\n", [
            '<?php',
            '',
            'namespace App\Models;',
            '',
            'use Illuminate\Database\Eloquent\Model;',
            '',
            'class '.$facts->modelClass().' extends Model',
            '{',
            '    //',
            '}',
        ]);
    }

    public static function controller(ProblemFacts $facts): string
    {
        return implode("\n", [
            '<?php',
            '',
            'namespace App\Http\Controllers;',
            '',
            'use Illuminate\Http\Request;',
            '',
            'class '.$facts->controllerClass().' extends Controller',
            '{',
            '    //',
            '}',
        ]);
    }

    public static function routes(): string
    {
        return implode("\n", [
            '<?php',
            '',
            'use Illuminate\Support\Facades\Route;',
            '',
            "Route::get('/', function () {",
            "    return view('welcome');",
            '});',
        ]);
    }
}

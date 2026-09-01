<?php

namespace App\Guides\Stubs;

use App\Guides\ProblemFacts;
use Illuminate\Support\Str;

class Ci4Stubs
{
    public static function defaultTable(ProblemFacts $facts): string
    {
        return Str::plural(Str::lower($facts->modelClass()));
    }

    public static function model(ProblemFacts $facts): string
    {
        return implode("\n", [
            '<?php',
            '',
            'namespace App\Models;',
            '',
            'use CodeIgniter\Model;',
            '',
            'class '.$facts->modelClass().' extends Model',
            '{',
            "    protected \$table            = '".self::defaultTable($facts)."';",
            "    protected \$primaryKey       = 'id';",
            '    protected $useAutoIncrement = true;',
            "    protected \$returnType       = 'array';",
            '    protected $useSoftDeletes   = false;',
            '    protected $protectFields    = true;',
            '    protected $allowedFields    = [];',
            '',
            '    protected bool $allowEmptyInserts = false;',
            '    protected bool $updateOnlyChanged = true;',
            '',
            '    protected array $casts = [];',
            '    protected array $castHandlers = [];',
            '',
            '    // Dates',
            '    protected $useTimestamps = false;',
            "    protected \$dateFormat    = 'datetime';",
            "    protected \$createdField  = 'created_at';",
            "    protected \$updatedField  = 'updated_at';",
            "    protected \$deletedField  = 'deleted_at';",
            '',
            '    // Validation',
            '    protected $validationRules      = [];',
            '    protected $validationMessages   = [];',
            '    protected $skipValidation       = false;',
            '    protected $cleanValidationRules = true;',
            '',
            '    // Callbacks',
            '    protected $allowCallbacks = true;',
            '    protected $beforeInsert   = [];',
            '    protected $afterInsert    = [];',
            '    protected $beforeUpdate   = [];',
            '    protected $afterUpdate    = [];',
            '    protected $beforeFind     = [];',
            '    protected $afterFind      = [];',
            '    protected $beforeDelete   = [];',
            '    protected $afterDelete    = [];',
            '}',
        ]);
    }

    public static function controller(ProblemFacts $facts): string
    {
        return implode("\n", [
            '<?php',
            '',
            'namespace App\Controllers;',
            '',
            'use App\Controllers\BaseController;',
            'use CodeIgniter\HTTP\ResponseInterface;',
            '',
            'class '.$facts->controllerClass().' extends BaseController',
            '{',
            '    public function index()',
            '    {',
            '        //',
            '    }',
            '}',
        ]);
    }

    public static function routes(): string
    {
        return implode("\n", [
            '<?php',
            '',
            'use CodeIgniter\Router\RouteCollection;',
            '',
            '/** @var RouteCollection $routes */',
            "\$routes->get('/', 'Home::index');",
        ]);
    }
}

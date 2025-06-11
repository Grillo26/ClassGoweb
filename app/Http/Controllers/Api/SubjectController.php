<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    use ApiResponser;

    /**
     * Obtener todas las materias
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Subject::select('id', 'name');

        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = trim($request->keyword);
            
            // Validar que la longitud del keyword sea razonable
            if (strlen($keyword) > 0 && strlen($keyword) <= 100) {
                $keyword = Str::lower($this->removeAccents($keyword));
                $query->where(function($q) use ($keyword) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%' . $keyword . '%'])
                      ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $this->removeAccents($keyword) . '%']);
                });
            }
        }

        $perPage = $request->get('per_page', 20); // Por defecto 20 por página
        $subjects = $query->paginate($perPage);
        return $this->success($subjects, 'Materias obtenidas exitosamente');
    }

    /**
     * Elimina acentos de una cadena
     *
     * @param string $string
     * @return string
     */
    private function removeAccents($string)
    {
        if (empty($string)) {
            return '';
        }

        $unwanted_array = array(
            'á'=>'a', 'à'=>'a', 'ã'=>'a', 'â'=>'a', 'ä'=>'a',
            'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e',
            'í'=>'i', 'ì'=>'i', 'î'=>'i', 'ï'=>'i',
            'ó'=>'o', 'ò'=>'o', 'õ'=>'o', 'ô'=>'o', 'ö'=>'o',
            'ú'=>'u', 'ù'=>'u', 'û'=>'u', 'ü'=>'u',
            'ý'=>'y', 'ÿ'=>'y',
            'ñ'=>'n',
            'Á'=>'A', 'À'=>'A', 'Ã'=>'A', 'Â'=>'A', 'Ä'=>'A',
            'É'=>'E', 'È'=>'E', 'Ê'=>'E', 'Ë'=>'E',
            'Í'=>'I', 'Ì'=>'I', 'Î'=>'I', 'Ï'=>'I',
            'Ó'=>'O', 'Ò'=>'O', 'Õ'=>'O', 'Ô'=>'O', 'Ö'=>'O',
            'Ú'=>'U', 'Ù'=>'U', 'Û'=>'U', 'Ü'=>'U',
            'Ý'=>'Y',
            'Ñ'=>'N'
        );
        return strtr($string, $unwanted_array);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanyCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Deshabilitar verificaciones de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiar tablas existentes
        DB::table('company_course_exam_questions')->truncate();
        DB::table('company_course_exams')->truncate();
        DB::table('company_courses')->truncate();
        
        // Insertar cursos
        $courses = [
            [
                'id' => 1,
                'name' => 'TEMA: INCLUSION Y ACCESIBILIDAD',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/IwBDl6EvLqk?si=bZkt5rFandrByKjQ',
                'description' => 'INCLUSION Y ACCESIBILIDAD',
                'created_at' => '2025-06-24 16:52:41',
                'updated_at' => '2025-06-24 19:58:41'
            ],

            [
                'id' => 2,
                'name' => 'TEMA: PERSONALIZACION DE TUTORES',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/gnuWqD1P7_8?si=49qHxDgdpUrqtZI8',
                'description' => 'PERSONALIZACION DE TUTORES',
                'created_at' => '2025-06-24 17:10:39',
                'updated_at' => '2025-06-24 19:58:34'
            ],
            [
                'id' => 3,
                'name' => 'TEMA: EMPATIA Y COMUNICACION',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/amr4JtNwScc?si=fOg45KLbb4SKVx4T',
                'description' => ' EMPATIA Y COMUNICACION',
                'created_at' => '2025-06-24 17:21:49',
                'updated_at' => '2025-06-24 19:58:25'
            ],
            [
                'id' => 4,
                'name' => 'TEMA: ESTRATEGIA DE ENSEÑANZAS EN SESIONES DE 20 MINUTOS',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/TdQqptGmHMc?si=CKZPgL2ErWtRK2lG',
                'description' => 'ESTRATEGIA DE ENSEÑANZAS EN SESIONES DE 20 MINUTOS',
                'created_at' => '2025-06-24 18:00:32',
                'updated_at' => '2025-06-24 19:57:00'
            ],
            [
                'id' => 5,
                'name' => 'TEMA: GAMIFICACION  APLICADA A LA TUTORIA EN LINEA',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/WrjoGNRpqW4?si=HeoaY-N2rdunuOsN',
                'description' => 'GAMIFICACION  APLICADA A LA TUTORIA EN LINEA',
                'created_at' => '2025-06-24 18:11:27',
                'updated_at' => '2025-06-24 18:11:27'
            ],
            [
                'id' => 6,
                'name' => 'TEMA: USO DE HERRAMIENTAS INTERACTIVAS EN TUTORIAS',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/U3WPwgrR42w?si=VCcmV9pB8RCkmk-o',
                'description' => ' USO DE HERRAMIENTAS INTERACTIVAS EN TUTORIAS',
                'created_at' => '2025-06-24 18:40:05',
                'updated_at' => '2025-06-24 19:58:15'
            ],
            [
                'id' => 7,
                'name' => 'TEMA: CREACION DE RECURSOS EDUCATIVOS EN TUTORIAS',
                'instructor_name' => 'Edward',
                'video_url' => 'https://www.youtube.com/watch?v=YP6jiSDMBcA',
                'description' => ' CREACION DE RECURSOS EDUCATIVOS EN TUTORIAS',
                'created_at' => '2025-06-24 18:40:05',
                'updated_at' => '2025-06-24 19:58:15'
            ],
            [
                'id' => 8,
                'name' => 'TEMA: NAVEGACION EFICIENTE EN CLASSGO',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/LtMKAbABqzg?si=tSQkZk9LrOpuosux',
                'description' => 'NAVEGACION EFICIENTE EN CLASSGO',
                'created_at' => '2025-06-24 19:19:46',
                'updated_at' => '2025-06-24 19:57:33'
            ],

            [
                'id' => 9,
                'name' => 'TEMA: EVALUACION RAPIDA DE CONOCIMIENTOS',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/L7snyyni5Z0?si=Rc5E1m_u8wbC6-EN',
                'description' => 'EVALUACION RAPIDA DE CONOCIMIENTOS',
                'created_at' => '2025-06-24 19:03:43',
                'updated_at' => '2025-06-24 19:58:02'
            ],

            [
                'id' => 10,
                'name' => 'TEMA: ESTRATEGIA PARA ENSEÑAR TEMAS COMPLEJOS',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/LtMKAbABqzg?si=tSQkZk9LrOpuosux',
                'description' => 'ESTRATEGIA PARA ENSEÑAR TEMAS COMPLEJOS',
                'created_at' => '2025-06-24 19:19:46',
                'updated_at' => '2025-06-24 19:57:33'
            ],
            [
                'id' => 11,
                'name' => 'TEMA: CONTRUCCION DE RUBRICAS SIMPLIFICADAS PARA FEEDBACK',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/LtMKAbABqzg?si=tSQkZk9LrOpuosux',
                'description' => 'CONTRUCCION DE REBRICAS SIMPLIFICADAS PARA FEEDBACK',
                'created_at' => '2025-06-24 19:26:55',
                'updated_at' => '2025-06-24 19:57:26'
            ],
            [
                'id' => 12,
                'name' => 'TEMA: DIVERSIDAD Y ADAPTABILIDAD EN TUTORIA',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/nEFOAe09xvs?si=YifVnU-hzsjfH7C7',
                'description' => 'DIVERSIDAD Y ADAPTABILIDAD EN TUTORIA',
                'created_at' => '2025-06-24 19:31:18',
                'updated_at' => '2025-06-24 19:57:52'
            ],
            [
                'id' => 13,
                'name' => 'TEMA: TENDENCIAS EN EDUCACION Y TECNOLOGIA',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/zF_0GQDb0x8?si=8ykKsgqTJ5hqouzG',
                'description' => 'TENDENCIAS EN EDUCACION Y TECNOLOGIA',
                'created_at' => '2025-06-24 19:43:23',
                'updated_at' => '2025-06-24 19:43:23'
            ],
            [
                'id' => 14,
                'name' => 'TEMA: CONSTRUCCION DE UNA MARCA PERSONAL COMO TUTOR',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/M6ZFoy6qIh4?si=FDy2k8-dnoDAJJfO',
                'description' => 'CONSTRUCCION DE UNA MARCA PERSONAL COMO TUTOR',
                'created_at' => '2025-06-24 19:53:08',
                'updated_at' => '2025-06-24 19:57:43'
            ],
            [
                'id' => 15,
                'name' => 'TEMA: DESARROLLO PROFESIONAL PARA TUTORES VIRTUALES',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/Y7-GPKVR-ZA?si=6kW2_WxADlUK1sMn',
                'description' => 'INCLUSION Y ACCESIBILIDAD',
                'created_at' => '2025-06-24 20:08:59',
                'updated_at' => '2025-06-24 20:08:59'
            ],
            [
                'id' => 16,
                'name' => 'TEMA: MANEJO DEL ESTRES Y LA MOTIVACION EN TUTORIA CORTAS',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/4O_olDwGJss?si=GhfLqiCRbqzGXf3R',
                'description' => 'MANEJO DEL ESTRES Y LA MOTIVACION EN TUTORIA CORTAS',
                'created_at' => '2025-06-24 20:36:12',
                'updated_at' => '2025-06-24 20:36:12'
            ],
            [
                'id' => 17,
                'name' => 'TEMA: METODOLOGIA ACTIVAS PARA TUTORIAS EN LINEA',
                'instructor_name' => 'Edward',
                'video_url' => 'https://youtu.be/4O_olDwGJss?si=GhfLqiCRbqzGXf3R',
                'description' => 'METODOLOGIA ACTIVAS PARA TUTORIAS EN LINEA',
                'created_at' => '2025-06-24 20:36:12',
                'updated_at' => '2025-06-24 20:36:12'
            ],
            [
                'id' => 18,
                'name' => 'TEMA: CERTIFICACION EN TUTORIA EFICAZ ONLINE CON CLASSGO',
                'instructor_name' => 'Edward',
                'video_url' => 'https://www.youtube.com/watch?v=94g56esVFPY',
                'description' => 'CERTIFICACION EN TUTORIA EFICAZ ONLINE CON CLASSGO',
                'created_at' => '2025-06-24 20:36:12',
                'updated_at' => '2025-06-24 20:36:12'
            ]
        ];
    
        DB::table('company_courses')->insert($courses);

        // Insertar exámenes
        $exams = [
            [
                'id' => 1,
                'company_course_id' => 1,
                'title' => 'Examen de TEMA 1: INCLUSION Y ACCESIBILIDAD',
                'total_score' => 100,
                'created_at' => '2025-06-24 16:52:42',
                'updated_at' => '2025-06-24 16:52:42'
            ],
            [
                'id' => 2,
                'company_course_id' => 2,
                'title' => 'Examen de TEMA 2: PERSONALIZACION DE TUTORES',
                'total_score' => 100,
                'created_at' => '2025-06-24 17:10:39',
                'updated_at' => '2025-06-24 17:10:39'
            ],
            [
                'id' => 3,
                'company_course_id' => 5,
                'title' => 'Examen de TEMA 3: EMPATIA Y COMUNICACION',
                'total_score' => 100,
                'created_at' => '2025-06-24 17:21:49',
                'updated_at' => '2025-06-24 17:21:49'
            ],
            [
                'id' => 4,
                'company_course_id' => 4,
                'title' => 'Examen de Tema 4 ESTRATEGIA DE ENSEÑANZAS EN SESIONES DE 20 MINUTOS',
                'total_score' => 100,
                'created_at' => '2025-06-24 18:00:32',
                'updated_at' => '2025-06-24 18:00:32'
            ],
            [
                'id' => 5,
                'company_course_id' => 5,
                'title' => 'Examen de TEMA5: GAMIFICACION  APLICADA A LA TUTORIA EN LINEA',
                'total_score' => 100,
                'created_at' => '2025-06-24 18:11:27',
                'updated_at' => '2025-06-24 18:11:27'
            ],
            [
                'id' => 6,
                'company_course_id' => 6,
                'title' => 'Examen de TEMA 6: USO DE HERRAMIENTAS INTERACTIVAS EN TUTORIAS',
                'total_score' => 100,
                'created_at' => '2025-06-24 18:40:05',
                'updated_at' => '2025-06-24 18:40:05'
            ],
            [
                'id' => 7,
                'company_course_id' => 9,
                'title' => 'Examen de TEMA 9: EVALUACION RAPIDA DE CONOCIMIENTOS',
                'total_score' => 100,
                'created_at' => '2025-06-24 19:03:43',
                'updated_at' => '2025-06-24 19:03:43'
            ],
            [
                'id' => 8,
                'company_course_id' => 10,
                'title' => 'Examen de TEMA 10: ESTRATEGIA PARA ENSEÑAR TEMAS COMPLEJOS',
                'total_score' => 100,
                'created_at' => '2025-06-24 19:19:46',
                'updated_at' => '2025-06-24 19:19:46'
            ],
            [
                'id' => 9,
                'company_course_id' => 11,
                'title' => 'Examen de TEMA 11: CONTRUCCION DE REBRICAS SIMPLIFICADAS PARA FEEDBACK',
                'total_score' => 100,
                'created_at' => '2025-06-24 19:26:55',
                'updated_at' => '2025-06-24 19:26:55'
            ],
            [
                'id' => 10,
                'company_course_id' => 12,
                'title' => 'Examen de TEMA 12: DIVERSIDAD Y ADAPTABILIDAD EN TUTORIA',
                'total_score' => 100,
                'created_at' => '2025-06-24 19:31:18',
                'updated_at' => '2025-06-24 19:31:18'
            ],
            [
                'id' => 11,
                'company_course_id' => 13,
                'title' => 'Examen de TEMA13: TENDENCIAS EN EDUCACION Y TECNOLOGIA',
                'total_score' => 100,
                'created_at' => '2025-06-24 19:43:23',
                'updated_at' => '2025-06-24 19:43:23'
            ],
            [
                'id' => 12,
                'company_course_id' => 14,
                'title' => 'Examen de TEMA 14: CONSTRUCCION DE UNA MARCA PERSONAL COMO TUTOR',
                'total_score' => 100,
                'created_at' => '2025-06-24 19:53:08',
                'updated_at' => '2025-06-24 19:53:08'
            ],
            [
                'id' => 13,
                'company_course_id' => 15,
                'title' => 'Examen de TEMA15: DESARROLLO PROFESIONAL PARA TUTORES VIRTUALES',
                'total_score' => 100,
                'created_at' => '2025-06-24 20:08:59',
                'updated_at' => '2025-06-24 20:08:59'
            ],
            [
                'id' => 14,
                'company_course_id' => 16,
                'title' => 'Examen de TEMA16: MANEJO DEL ESTRES Y LA MOTIVACION EN TUTORIA CORTAS',
                'total_score' => 100,
                'created_at' => '2025-06-24 20:36:12',
                'updated_at' => '2025-06-24 20:36:12'
            ],
            [
                'id' => 15,
                'company_course_id' => 17,
                'title' => 'Examen de TEMA 17: METODOLOGIA ACTIVAS PARA TUTORIAS EN LINEA',
                'total_score' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 16,
                'company_course_id' => 18,
                'title' => 'Examen de TEMA 18: CERTIFICACION EN TUTORIA EFICAZ ONLINE CON CLASSGO',
                'total_score' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 17,
                'company_course_id' => 7,
                'title' => 'Examen de TEMA 7: CREACION DE RECURSOS EDUCATIVOS EN TUTORIAS',
                'total_score' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 18,
                'company_course_id' => 8,
                'title' => 'Examen de TEMA 8: NAVEGACION EFICIENTE EN CLASSGO',
                'total_score' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('company_course_exams')->insert($exams);

        // Insertar preguntas de exámenes
        $questions = [
            // Preguntas para examen ID 3 (GAMIFICACION)
            [
                'id' => 26,
                'company_course_exam_id' => 3,
                'question' => 'La gamificación en Tutorías es',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['Juegos dinámicos de cualquier tema', 'Estrategia educativa que aumente la participación y compromiso', 'ninguna']),
                'correct_answer' => '2',
                'created_at' => '2025-06-24 18:11:27',
                'updated_at' => '2025-06-24 18:11:27'
            ],
            [
                'id' => 27,
                'company_course_exam_id' => 3,
                'question' => 'elementos de la gamificacion',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['a) puntos, insignias, desafíos', 'b) Puntos, insignias, desafíos', 'c) puntos, desafíos, juegos']),
                'correct_answer' => '1',
                'created_at' => '2025-06-24 18:11:27',
                'updated_at' => '2025-06-24 18:11:27'
            ],
            [
                'id' => 28,
                'company_course_exam_id' => 3,
                'question' => 'selecciona un ejemplo de la gamificación',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['a) Diseña, un juego de preguntas y respuestas', 'b) ninguna', 'c) competencia de viajar de un lugar a otra']),
                'correct_answer' => '1',
                'created_at' => '2025-06-24 18:11:27',
                'updated_at' => '2025-06-24 18:11:27'
            ],
            // Preguntas para examen ID 13 (TENDENCIAS)
            [
                'id' => 44,
                'company_course_exam_id' => 13,
                'question' => '1) Evolución de la Enseñanza virtual',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['a) videollamada o experiencias inmersivas a cambio de expectativas', 'b) videollamada a videoconferencia', 'c) videollamada , experiencia inmersiva a necesidad de adaptación del tutor', 'd) ninguna']),
                'correct_answer' => '3',
                'created_at' => '2025-06-24 19:43:23',
                'updated_at' => '2025-06-24 19:43:23'
            ],
            [
                'id' => 45,
                'company_course_exam_id' => 13,
                'question' => 'Principales tendencias en educación',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['a) microlearning , gamificación, , aprendizaje adaptativo', 'b) microlearning ,aplicaciones , plataformas', 'c) ninguna']),
                'correct_answer' => '1',
                'created_at' => '2025-06-24 19:43:23',
                'updated_at' => '2025-06-24 19:43:23'
            ],
            // Preguntas para examen ID 4 (ESTRATEGIA)
            [
                'id' => 52,
                'company_course_exam_id' => 4,
                'question' => '1) ESTRATEGIA',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['a) Define la sesión', 'b) claro y especifico', 'c) ninguna']),
                'correct_answer' => '2',
                'created_at' => '2025-06-24 19:57:00',
                'updated_at' => '2025-06-24 19:57:00'
            ],
            [
                'id' => 53,
                'company_course_exam_id' => 4,
                'question' => 'Una de Las Estrategias de enseñanza en sesiones de 20 min es la interacción constante',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['falso', 'verdadero']),
                'correct_answer' => '2',
                'created_at' => '2025-06-24 19:57:00',
                'updated_at' => '2025-06-24 19:57:00'
            ],
            [
                'id' => 54,
                'company_course_exam_id' => 4,
                'question' => 'Errores Comunes',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['Planificar Todo', 'Intentar cubrir muchos temas a la vez', 'ninguno']),
                'correct_answer' => '2',
                'created_at' => '2025-06-24 19:57:00',
                'updated_at' => '2025-06-24 19:57:00'
            ],
            // Preguntas para examen ID 9 (RUBRICAS)
            [
                'id' => 55,
                'company_course_exam_id' => 9,
                'question' => '1. la Rubrica',
                'type' => 'opcion_unica',
                'score' => 30,
                'options' => json_encode(['A) Permite valorar el desempeño del estudiante en diferentes niveles', 'B) Da un enfoque personalizado desde el primer minuto', 'C) Espacio para dudas y comentarios']),
                'correct_answer' => '1',
                'created_at' => '2025-06-24 19:57:26',
                'updated_at' => '2025-06-24 19:57:26'
            ],
            [
                'id' => 56,
                'company_course_exam_id' => 9,
                'question' => '2. Componente de una rubrica simplificada',
                'type' => 'opcion_unica',
                'score' => 30,
                'options' => json_encode(['A) Criterio, nivel de logro y dudas', 'B) Criterio, niveles, comentarios, dudas y logros', 'C) Criterio, niveles de logro y comentario']),
                'correct_answer' => '3',
                'created_at' => '2025-06-24 19:57:26',
                'updated_at' => '2025-06-24 19:57:26'
            ],
            // Preguntas para examen ID 8 (TEMAS COMPLEJOS)
            [
                'id' => 57,
                'company_course_exam_id' => 8,
                'question' => '1. Claves para simplificar temas complejos',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['A) Divide el tema en partes esenciales', 'B) Todas', 'C) Usa lenguaje corto y consiso']),
                'correct_answer' => '2',
                'created_at' => '2025-06-24 19:57:33',
                'updated_at' => '2025-06-24 19:57:33'
            ],
            [
                'id' => 58,
                'company_course_exam_id' => 8,
                'question' => '2. El uso de analogías permiten conectar un concepto difícil:',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['FALSO', 'VERDADERO']),
                'correct_answer' => '2',
                'created_at' => '2025-06-24 19:57:33',
                'updated_at' => '2025-06-24 19:57:33'
            ],
            [
                'id' => 59,
                'company_course_exam_id' => 10,
                'question' => '3. En que ayudan el uso de tecnología y recursos digitales:',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['A) Pueden ayudar a que expliques menos', 'B) Pueden hacer mas accesibles los temas complejos', 'C) Aprendes de manera rápida']),
                'correct_answer' => '2',
                'created_at' => '2025-06-24 19:57:33',
                'updated_at' => '2025-06-24 19:57:33'
            ],
            // Preguntas para examen ID 12 (MARCA PERSONAL)
            [
                'id' => 60,
                'company_course_exam_id' => 14,
                'question' => '1) Una marca personal es',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['a) percepción que los estudiantes tienen sobre ti', 'b) Percepción que se tiene sobre algo', 'c) ambas']),
                'correct_answer' => '3',
                'created_at' => '2025-06-24 19:57:43',
                'updated_at' => '2025-06-24 19:57:43'
            ],
            [
                'id' => 61,
                'company_course_exam_id' => 14,
                'question' => '2) El primer paso para crear tu marca personal en las tutorías es',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['a) Definir tu vestimenta de tutor', 'b) Definir Tu identidad de Tutor', 'c) Definir Tu forma de hablar como Tutor']),
                'correct_answer' => '2',
                'created_at' => '2025-06-24 19:57:43',
                'updated_at' => '2025-06-24 19:57:43'
            ],
            [
                'id' => 62,
                'company_course_exam_id' => 14,
                'question' => '3) La marca personal se construye a través de',
                'type' => 'opcion_unica',
                'score' => 20,
                'options' => json_encode(['a) Experiencia, autenticidad y conexión con los estudiantes', 'b) Experiencia, autenticidad y conexión con los padres', 'c) Experiencia, autenticidad y conexión con la comunidad']),
                'correct_answer' => '1',
                'created_at' => '2025-06-24 19:57:43',
                'updated_at' => '2025-06-24 19:57:43'
            ],
        ];

        DB::table('company_course_exam_questions')->insert($questions);

        // Rehabilitar verificaciones de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

        

       
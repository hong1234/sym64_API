<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Dao\BookDao;

#[Route('/api')]
class BookController extends AbstractController {

    private $bookDao;
    
    public function __construct(BookDao $bDao) {
        $this->bookDao = $bDao;
    }

    #[Route('/books', name: 'book_add', methods: ['POST'])]
    public function bookAdd(Request $request): JsonResponse {
        $error = '';
        $rs = [];

        // if ($request->isMethod('GET')) {
            // do something
            // return ...
        // }

        // if ($request->isMethod('POST')) {
            // do something
            // return ...
        // }

        // do something by POST 

        $data = json_decode($request->getContent(), true); // array()
            
        if($data != null){
            $rowCount = $this->bookDao->bookInsert([
                'title'   => $data['title'],
                'content' => $data['content']
            ]);

            $rs = [
                "code"    => "200",
                "message" => "{$rowCount} Book angelegt"
            ];

        } else {
            $rs = [
                "code"    => "401",
                "message" => "Input-parameters sind json-invalid"
            ];
        }
        
        return $this->json($rs);
    }

    #[Route('/books/{id}', name: 'book_update', methods: ['PUT'])]
    public function bookUpdate(int $id, Request $request): JsonResponse {
        $error = '';
        $rs = [];

        $result_array = $this->bookDao->getBook(['id' => $id]);

        if(count($result_array)>0) {

            $data = json_decode($request->getContent(), true); // array()
            
            if($data != null){
                $rowCount = $this->bookDao->bookUpdate([
                    'id'      => $id,
                    'title'   => $data['title'],
                    'content' => $data['content']
                ]);

                $rs = [
                    "code"    => "200",
                    "message" => "Book {$id} updated"
                ];
   
            } else {
                $rs = [
                    "code"    => "401",
                    "message" => "Input-parameters are json-invalid"
                ];
            }

        } else {
            $rs = [
                "code"    => "401",
                "message" => "Book Id {$id} don't exist"
            ];
        }
        
        return $this->json($rs);
    }

    #[Route('/books/{id}', name: 'book_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function bookDelete(int $id): JsonResponse {

        $result_array = $this->bookDao->getBook(['id' => $id]);

        if(count($result_array)>0) {
            $this->bookDao->bookDelete(['id' => $id]);

            $rs = [
                "code"    => "200",
                "message" => "Book {$id} wurde gelöscht"
            ];
        } else {
            $rs = [
                "code"    => "401",
                "message" => "Book Id {$id} don't exist"
            ];
        }
        
        return $this->json($rs);
    }

    #[Route('/books', name: 'book_all', methods: ['GET'])]
    public function bookAll() : JsonResponse { 
        
        $result_array = $this->bookDao->bookAll();

        $rs = [
            "code" => "200",
            "data" => $result_array
        ];
        return $this->json($rs);
    }
    
}
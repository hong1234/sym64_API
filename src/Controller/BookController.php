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

    #[Route('/books/insert', name: 'book_insert', methods: ['POST'])]
    public function bookInsert(Request $request): JsonResponse {
        $error = '';
        $rs = [];

        if ($request->isMethod('POST')) {

            $data = json_decode($request->getContent(), true); // array()
            
            if($data != null){
                $rowCount = $this->bookDao->bookInsert([
                    'title'   => $data['title'],
                    'content' => $data['content']
                ]);

                $rs["status"] = [
                    "statuscode" => "200", 
                    "message" => "Book angelegt",
                    "error" => $error
                ];

                $rs["data"] = $rowCount; // $data;
                
            } else {
                $rs["status"] = [
                    "statuscode" => "401", 
                    "message" => "Book nicht angelegt",
                    "error" => "Input-Parameters sind json-invalid"
                ];
            }
        }
        
        return $this->json($rs);
    }

    #[Route('/books', name: 'book_all', methods: ['GET'])]
    public function bookAll() : JsonResponse {  
        $result_array = $this->bookDao->bookAll()->fetchAllAssociative();
        return $this->json($result_array);
    }

    #[Route('/books/{id}', name: 'book_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function accountDelete(int $id): JsonResponse {
        $error = '';
        $rs = [];
        $this->bookDao->bookDelete(['id' => $id]);
        $rs["status"] = [
            "statuscode" => "200", 
            "message" => "Book {$id} wurde gelöscht",
            "error" => $error
        ];
        return $this->json($rs);
    }
}
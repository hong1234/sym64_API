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

                $rs["code"] = "200";
                $rs["message"] = "{$rowCount} Book angelegt";
   
            } else {
                $rs["code"] = "401";
                $rs["message"] = "Input-parameters sind json-invalid";
            }
        }
        
        return $this->json($rs);
    }

    #[Route('/books', name: 'book_all', methods: ['GET'])]
    public function bookAll() : JsonResponse { 
        $rs = [];
        $result_array = $this->bookDao->bookAll()->fetchAllAssociative();
        $rs["code"] = "200";
        $rs["data"] = $result_array;
        return $this->json($rs);
    }

    #[Route('/books/{id}', name: 'book_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function bookDelete(int $id): JsonResponse {
        // $error = '';
        $rs = [];
        $this->bookDao->bookDelete(['id' => $id]);
        $rs["code"] = "200";
        $rs["message"] = "Book {$id} wurde gelöscht";
        return $this->json($rs);
    }
}
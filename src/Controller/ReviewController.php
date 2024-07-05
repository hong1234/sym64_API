<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Dao\ReviewDao;

#[Route('/api')]
class ReviewController extends AbstractController {

    private $reviewDao;
    
    public function __construct(ReviewDao $rwDao) {
        $this->reviewDao = $rwDao;
    }

    #[Route('/reviews/{bookid}', name: 'review_add', requirements: ['bookid' => '\d+'], methods: ['POST'])]
    public function reviewAdd(Request $request, int $bookid): JsonResponse {
        $error = '';
        $rs = [];

        $data = json_decode($request->getContent(), true); // array()
            
        if($data != null){
            $rowCount = $this->reviewDao->reviewInsert([
                'email'       => $data['email'],
                'name'        => $data['name'],
                'content'     => $data['content'],
                'like_status' => $data['like_status'], // Low, Medium, High
                'book_id'     => $bookid
            ]);

            $rs = [
                "code"    => "200",
                "message" => "{$rowCount} Review created"
            ];

        } else {
            $rs = [
                "code"    => "401",
                "message" => "Input-parameters are json-invalid"
            ];
        }
        
        return $this->json($rs);
    }

    #[Route('/reviews/{reviewid}', name: 'review_delete', requirements: ['reviewid' => '\d+'], methods: ['DELETE'])]
    public function reviewDelete(int $reviewid): JsonResponse {

        $result_array = $this->reviewDao->getReview(['id' => $reviewid]);

        if(count($result_array)>0) {
            $this->reviewDao->reviewDelete(['id' => $reviewid]);

            $rs = [
                "code"    => "200",
                "message" => "Review {$reviewid} deleted"
            ];

        } else {
            $rs = [
                "code"    => "401",
                "message" => "Review Id {$reviewid} don't exist"
            ];
        }
        
        return $this->json($rs);
    }

    #[Route('/reviews/{bookid}', name: 'review_all', requirements: ['bookid' => '\d+'], methods: ['GET'])]
    public function reviewsOfBookId(int $bookid) : JsonResponse { 
        
        $result_array = $this->reviewDao->reviewsByBookId(['book_id' => $bookid]);

        $rs = [
            "code" => "200",
            "data" => $result_array
        ];
        return $this->json($rs);
    }

}
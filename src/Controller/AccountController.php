<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Dao\AccountDao;

#[Route('/api')]
class AccountController extends AbstractController {

    private $accountDao;
    
    public function __construct(AccountDao $acDao) {
        $this->accountDao = $acDao;
    }

    #[Route('/account', name: 'acc_add', methods: ['POST'])]
    public function accountAdd(Request $request): JsonResponse {
        $error = '';
        $rs = [];

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true); // array()
            
            if($data != null){
                $rowCount = $this->accountDao->accountInsert([
                    'username' => $data['username'],
                    'password' => $data['password']
                ]);

                $rs = [
                    "code"    => "200",
                    "message" => "{$rowCount} Account angelegt"
                ];

            } else {
                $rs = [
                    "code"    => "401",
                    "message" => "Input-parameters sind json-invalid"
                ];
            }
        }
        
        return $this->json($rs);
    }

    #[Route('/account/{id}', name: 'acc_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function accountDelete(int $id): JsonResponse {
        $rs = [];

        $result_array = $this->accountDao->getAccount(['id' => $id]);
        if(count($result_array)>0) {

            $this->accountDao->accountDelete(['id' => $id]);
            $rs = [
                "code"    => "200",
                "message" => "Account {$id} wurde gelöscht"
            ];
            
        } else {
            $rs = [
                "code"    => "401",
                "message" => "Book {$id} dont exist"
            ];
        }
        
        return $this->json($rs);
    }

    #[Route('/account', name: 'acc_all', methods: ['GET'])]
    public function accAll() : JsonResponse { 
        $result_array = $this->accountDao->accountAll();

        $rs = [
            "code" => "200",
            "data" => $result_array
        ];
        return $this->json($rs);
    }

    // #[Route('/account/filter', name: 'acc_filter')]
    // public function accFilter() : JsonResponse {  
    //     $params = [
    //         "username" => "poper"
    //     ];

    //     // $result = $this->accountDao->accountFilter($params);
    //     // $result_array = $result->fetchAllAssociative();

    //     $result_array = $this->accountDao->accountFilter($params);

    //     $rs = [
    //         "code" => "200",
    //         "data" => $result_array
    //     ];
    //     return $this->json($rs);
    // }

    // #[Route('/lucky/number/{max}', name: 'lucky_number')]
    // public function number(int $max): JsonResponse {
    //     $number  = random_int(0, $max);
    //     return $this->json(['number1' => $number]);
    // }

}
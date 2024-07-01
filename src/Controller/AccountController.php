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

    #[Route('/account/{id}', name: 'acc_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function accountDelete(int $id): JsonResponse {
        $error = '';
        $rs = [];
        $this->accountDao->accountDelete(['id' => $id]);
        $rs["status"] = [
            "statuscode" => "200", 
            "message" => "Account {$id} wurde gelöscht",
            "error" => $error
        ];
        return $this->json($rs);
    }

    #[Route('/account/insert', name: 'acc_insert', methods: ['POST'])]
    public function accountInsert(Request $request): JsonResponse {
        $error = '';
        $rs = [];

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true); // array()
            
            if($data != null){
                $rowCount = $this->accountDao->accountInsert([
                    'username' => $data['username'],
                    'password' => $data['password']
                ]);

                $rs["status"] = [
                    "statuscode" => "200", 
                    "message" => "Account erfolgreich angelegt",
                    "error" => $error
                ];

                $rs["data"] = $rowCount; // $data;
                
            } else {
                $rs["status"] = [
                    "statuscode" => "401", 
                    "message" => "Account kann nicht angelegt werden",
                    "error" => "--Input-Parameter(s) sind json-invalid"
                ];
            }
        }
        
        return $this->json($rs);
    }

    #[Route('/account/filter', name: 'acc_filter')]
    public function accFilter() : JsonResponse {  
        $params = [
            "username" => "poper"
        ];

        $result = $this->accountDao->accountFilter($params);
        $result_array = $result->fetchAllAssociative();
        return $this->json($result_array);
    }

    #[Route('/account', name: 'acc_all')]
    public function accAll() : JsonResponse {  
        $result_array = $this->accountDao->accountAll()->fetchAllAssociative();
        return $this->json($result_array);
    }

    // #[Route('/lucky/number/{max}', name: 'lucky_number')]
    // public function number(int $max): JsonResponse {
    //     $number  = random_int(0, $max);
    //     $number2 = random_int(0, $max);

    //     $data = [];
    //     $data[] = [
    //         'number1' => $number
    //     ];

    //     $data[] = [
    //         'number2' => $number2
    //     ];

    //     return $this->json($data);
    // }

}
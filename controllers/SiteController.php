<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Availability;
use app\models\Room;
use yii\helpers\Json;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    public function beforeAction($action)
    {            
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * index.php?r=site/index
     * GET request
     * $startDate
       $endDate
     * @return string
     * http://corfu.localhost/index.php?r=site/index&startDate=2023-01-15&endDate=2023-01-25
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        try {

            if ($request->isGet) {

                $startDate = $request->get('startDate');
                $endDate = $request->get('endDate');

                if (empty($startDate) || empty($endDate)) {

                    throw new \Exception('Parameters Missing');
                
                } else {

                    if ($startDate > $endDate) {
                        
                        throw new \Exception('Dates have problem');
                    }

                    $result = $this->getPeriod($startDate, $endDate);

                    $rooms = Room::find()->all();

                    foreach ($rooms as $room) {

                        $roomId = $room->id;
                        $roomType = $room->type;
                    
                        $availabilities = Availability::find()->select('calendar_date, status')->where(['roomId'=> $roomId])
                                        ->andFilterWhere(['>=', 'calendar_date', $startDate])
                                        ->andFilterWhere(['<=', 'calendar_date', $endDate])
                                        ->asArray()->all();

                        foreach ($availabilities as $available) {

                            if ($available['status'] == 'Y') {

                                    $result[$available['calendar_date']]['rooms'][$roomType] += 1;                             
                                    $result[$available['calendar_date']]['availability'] = true;  
                            }
                        }        
                    }
              
                    $returnData = [
                        "success" => true,
                        "data" => array_values($result)
                    ];
                }

                } else {

                    throw new \Exception('Not a get request');
                }

        } catch (\Exception $e) {
                    
            $returnData = [
                "success" => false,
                "data" => [
                    "name" => "Something went wrong",
                    "message" => $e->getMessage(),
                    "code" => 0,
                    "status" => 400
                ]
            ];
        }

        return $this->asJson($returnData);
    }

    /**
     *  create dates
     **/
    private function getPeriod($startDate, $endDate) {

        $period = new \DatePeriod(
             new \DateTime($startDate ),
             new \DateInterval('P1D'),
             new \DateTime(date('Y-m-d',strtotime($endDate.' +1 Day')))
        );

        $result = [];

        foreach ($period as $key => $value) {
            
            $result[$value->format('Y-m-d')] = [
                'date' => $value->format('Y-m-d'), 
                'rooms' => [
                    'double' => 0, 
                    'triple' => 0
                ], 
                'availability' => false
            ];       
        }

        return $result;
    }
}

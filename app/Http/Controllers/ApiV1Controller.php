<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
require_once __DIR__ . "/../../../vendor/taobao/TopClient.php";
require_once __DIR__ . "/../../../vendor/taobao/request/ItemcatsGetRequest.php"; 
require_once __DIR__ . "/../../../vendor/taobao/request/AtbItemsGetRequest.php";
require_once __DIR__ . "/../../../vendor/taobao/RequestCheckUtil.php";
require_once __DIR__ . "/../../../vendor/taobao/request/AtbItemsDetailGetRequest.php";
require_once __DIR__ . "/../../../vendor/taobao/request/TaeItemDetailGetRequest.php";
class ApiV1Controller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $appkey = '23426822';
    private $secretKey = 'c32092e3bfc627fafe2a4c9d39de75d1';
    private $topClient = null;
    private $reqArr = array();
    private $availableTypeArr = array(
        "家居饰品"=>"50020808","特色手工艺"=>"50020857","电子元器件市场"=>"50024099","男装"=>"30","住宅家具"=>"50008164",
        "商业/办公家具"=>"50020611","国货精品数码"=>"50023904","彩妆/香水/美妆工具"=>"50010788","美容护肤/美体/精油"=>"1801","美发护发/假发"=>"50023282",
        "手机"=>"1512","数码相机/单反相机/摄像机"=>"14","MP3/MP4/iPod/录音笔"=>"1201","笔记本电脑"=>"1101",
        "平板电脑/MID"=>"50019780","DIY电脑"=>"50018222","电脑硬件/显示器/电脑周边"=>"11","网络设备/网络相关"=>"50018264",
        "3C数码配件"=>"50008090","闪存卡/U盘/存储/移动硬盘"=>"50012164","办公设备/耗材/相关服务"=>"50007218","电子词典/电纸书/文化用品"=>"50018004",
        "电玩/配件/游戏/攻略"=>"20","大家电"=>"50022703","影音电器"=>"50011972","生活电器"=>"50012100","厨房电器"=>"50012082","个人护理/保健/按摩器材"=>"50002768",
        "家装主材"=>"27","合约机"=>"124912001","五金/工具"=>"50020485","婚庆/摄影/摄像服务"=>"50050471","居家日用"=>"21","厨房/烹饪用具"=>"50016349",
        "床上用品"=>"50008163","奶粉/辅食/营养品/零食"=>"35","尿片/洗护/喂哺/推车床"=>"50014812","孕妇装/孕产妇用品/营养"=>"50022517",
        "童装/婴儿装/亲子装"=>"50008165","传统滋补营养品"=>"50020275","零食/坚果/特产"=>"50002766","粮油米面/南北干货/调味品"=>"50016422",
        "运动/瑜伽/健身/球迷用品"=>"50010728","户外/登山/野营/旅行用品"=>"50013886","运动服/休闲服装"=>"50011699","玩具/童车/益智/积木/模型"=>"25","古董/邮币/字画/收藏"=>"23",
        "鲜花速递/花卉仿真/绿植园艺"=>"50007216","流行男鞋"=>"50011740","女装/女士精品"=>"50006843","女鞋"=>"50006843","箱包皮具/热销女包/男包"=>"50006842",
        "女士内衣/男士内衣/家居服"=>"1625","服饰配件/皮带/帽子/围巾"=>"50010404","ZIPPO/瑞士军刀/眼镜"=>"28","书籍/杂志/报纸"=>"33","音乐/影视/明星/音像"=>"34",
        "乐器/吉他/钢琴/配件"=>"50017300","宠物/宠物食品及用品"=>"29","运动鞋new"=>"50012029","饰品/流行首饰/时尚饰品新"=>"50013864","洗护清洁剂/卫生巾/纸/香薰"=>"50025705",
        "咖啡/麦片/冲饮"=>"50026316","保健食品/膳食营养补充食品"=>"50026800","水产肉类/新鲜蔬果/熟食"=>"50050359","手表"=>"50468001","运动包/户外包/配件"=>"50510002","酒类"=>"50008141",
        "童鞋/婴儿鞋/亲子鞋"=>"122650005","自行车/骑行装备/零配件"=>"122684003","家庭保健"=>"122718004","居家布艺"=>"122852001","节庆用品/礼品"=>"122950001","餐饮具"=>"122952001",
        "模玩/动漫/周边/cos/桌游床上用品"=>"124484008","茶"=>"124458005","二手数码"=>"124852003","床上用品"=>"50008163","床上用品"=>"50008163",
        "床上用品"=>"50008163","床上用品"=>"50008163","床上用品"=>"50008163","床上用品"=>"50008163","床上用品"=>"50008163",    
    );
    public function __construct()
    {
        //
    }

    private function getTopClient(){
        if(!$this->topClient){
            $this->topClient = new \TopClient;
        }
        return $this->topClient;
    }

    private function getRequest($req_name,$req){
        if(!array_key_exists($req_name,$this->reqArr)){
            $this->req[$req_name] = $req; 
        }
        return $this->req[$req_name];
    }
    
    public function getWholeCategory(){
        set_time_limit(0);
        $req_name = 'taobao.itemcats.get';
        $client = $this->getTopClient();
        $client->appkey = $this->appkey;
        $client->secretKey = $this->secretKey;
        $req = $this->getRequest($req_name,new \ItemcatsGetRequest);
        $req->setParentCid("0");
        $resp = $client->execute($req);
        echo '<pre>';
        var_dump($resp);
        echo '</pre>';
    }


    private function _getProducts($pageNum = 1,$pageSize){
        $req_name = "taobao.atb.items.get";
        $c = $this->getTopClient();
        $c->appkey = $this->appkey;
        $c->secretKey = $this->secretKey;
        $req = $this->getRequest($req_name,new \AtbItemsGetRequest);

        //参数
        $req->setFields("open_iid,title,nick,pic_url,price,commission,commission_rate,commission_num,commission_volume,seller_credit_score,item_location,volume");
        $req->setEndCommissionRate('9000');
        $req->setStartCommissionRate("1234");
        $req->setStartCredit("1crown");
        $req->setEndCredit("5goldencrown");
        $req->setEndPrice("999");
        $req->setStartPrice("20");
        $req->setStartTotalnum("10");
        $req->setEndTotalnum("10000");
        $req->setPageNo($pageNum);
        $req->setPageSize($pageSize);
        $req->setCid('50020611');
        $resp = $c->execute($req);
       
       // var_dump($resp);
        return $resp;
    }

    public function getProductsInfo(Request $request){
        set_time_limit(0);
        $data = array();
        $pageSize = 10;
        $openiidArr = array();
        $req_name = 'taobao.atb.items.detail.get';
        $c = $this->getTopClient();
        $c->appkey = $this->appkey;
        $c->secretKey = $this->secretKey;
        $req = $this->getRequest($req_name,new \AtbItemsDetailGetRequest);
        $req->setFields("open_iid,title,location,detail_url,auction_point,price,pic_url");//desc
        $infos = $this->_getProducts($request->query("page"),$pageSize);
        //var_dump(property_exists($infos,'items'));
        if(property_exists($infos,'items')){
             $data = (array)$infos->items;
        }
       
       // var_dump($data);die;
        if(array_key_exists('aitaobao_item',$data) && is_array($data['aitaobao_item']) && count($data['aitaobao_item'])>0){
           // if(!array_key_exists("error",$data)){
                 foreach ($data['aitaobao_item'] as $key => $value) {
                    $openiid = (array)$value->open_iid;
                    
                    $openiidArr[] = $openiid[0];
                    
                }
                $req->setOpenIids(implode(",",$openiidArr));
                $resp = $c->execute($req);
                $resp->page_num = $request->query("page");
                $resp->page_size = $pageSize;//->atb_item_details->aitaobao_item_detail
           
           
            
        }else{
             $resp['atb_item_details']['aitaobao_item_detail'] = array("error"=>true,"msg"=>"已无结果");
        }
        return response()->json($resp)->setCallback($request->query("callback"));
       // return response()->json($data)->setCallback($request->query("callback"));
        
        
    }

 
    public function getProductDetail(Request $request){
        set_time_limit(0);
        $data = array();
        $pageSize = 10;
        $openiidArr = array();
        $req_name = 'taobao.atb.items.detail.get';
        $c = $this->getTopClient();
        $c->appkey = $this->appkey;
        $c->secretKey = $this->secretKey;
        $req = $this->getRequest($req_name,new \AtbItemsDetailGetRequest);
        $req->setFields("open_iid,title,location,item_img,detail_url,auction_point,price,pic_url,desc");//desc
        $req->setOpenIids($request->query('openiid'));
        $resp = $c->execute($req);
        return response()->json($resp)->setCallback($request->query("callback"));
    } 

    public function getProductTAE(Request $request){
        set_time_limit(0);
        $data = array();
        $openiidArr = array();
        $req_name = 'taobao.tae.item.detail.get';
        $c = $this->getTopClient();
        $c->appkey = $this->appkey;
        $c->secretKey = $this->secretKey;
        $req = $this->getRequest($req_name,new \TaeItemDetailGetRequest);
        $req->setFields("itemInfo,priceInfo,mobileDescInfo");//desc
        $req->setOpenIid($request->query('openiid'));
        $resp = $c->execute($req);
        return response()->json($resp)->setCallback($request->query("callback"));
    }

    //
}


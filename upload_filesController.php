<?php
/**
 * Created by PhpStorm.
 * User: hm
 * Date: 2019/1/14
 * Time: 17:41
 */

class upload_filesController extends controller
{
    /*
        * ajax处理数据
        */
    public function getfilesAction()
    {
        $uuid = $_POST['uuid'];   //获取id
        if ($_FILES && $uuid) {
            $uuid = $_POST['uuid'];   //获取id
            $tmp_file_name = $_FILES['file']['tmp_name']; //得到上传后的临时文件
            $file_name = $_FILES['file']['name']; //源文件名
            $file_dir = __SITEROOT . 'upload/files/'; //最终保存目录
            $date = time();
            $name = explode('.', $file_name);
            $newPath = $date . '.' . $name[1];//得到一个新的文件
            if (is_dir($file_dir)) {
                move_uploaded_file($tmp_file_name, $file_dir . $newPath); //移动文件到最终保存目录
                $assess = new Tkpi_assess_clause();
                $assess->whereAdd("uuid= '$uuid'");
                $assess->file_path = $newPath;
                $assess->file_name = $name[0];
                $msg = $assess->update();
                if ($msg) {
                    $merchandise = '上传成功';
                    echo json_encode($merchandise);
                    exit;
                }
            }
        }
        $merchandise = '上传失败';
        echo json_encode($merchandise);
        exit;
    }

    /*
     * 下载文件
     */
    public function downloadAction()
    {

        $http = $_SERVER['HTTP_HOST'];
        $uuid = $this->_request->getParam('uuid');   //获取id
        //查
        $assess = new Tkpi_assess_clause();
        $assess->whereAdd("uuid= '$uuid'");
        $assess->find(true);
        $filename = $assess->file_path;
        $name = explode('.', $filename);
        $suffix_name = $name[1];//得到一个新的文件
        $url = '';
        //判断文件格式
        if ($suffix_name != 'pdf') {
//            ******************************************** phpexecl************************************
//            set_time_limit(0);
//            require_once __SITEROOT . 'library/phpexcel/Classes/PHPExcel.php';
//            require_once __SITEROOT . 'library/Classes/PHPExcel.php';
//            $sFileUrl= __SITEROOT . 'upload/files/'.$filename;// 你文件的地址
//            $sFileType = PHPExcel_IOFactory::identify($sFileUrl);//获取文件类型
//            $objReader = PHPExcel_IOFactory::createReader($sFileType);//创建读取
//            $objWriteHtml=new PHPExcel_Writer_HTML($objReader->load($sFileUrl, 'UTF-8'));//加载内容
//            echo $objWriteHtml->save("php://output");//输出html文件到页面
//            exit;
//            ******************************************** end************************************
            $url = 'http://www.xdocin.com/xdoc?_func=to&_format=html&_cache=1&_xdoc=http://' . $http;
        }
        $this->view->assign('url', $url);
        $this->view->assign('file_name', $filename);
        $this->view->display('pdf.html');
    }

}
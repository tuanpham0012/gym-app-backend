<?php
namespace App\Helpers;

use App\Models\OrderNote;
use App\Models\Notification;
use App\Events\NotificationPosted;

class Helpers
{
  public static function CreateOrderNote($order_id, $admin_id, $content){
    $note = new OrderNote();
    $note->order_id = $order_id;
    $note->admin_id = $admin_id;
    $note->note = $content;
    $note->save();
    return $note;
  }
  public static function randImage(){
    $image = array(
      ['https://taimienphi.vn/tmp/cf/aut/mAKI-top-anh-dai-dien-dep-chat-1.jpg'],
      ['https://imgt.taimienphi.vn/cf/Images/np/2020/1/3/top-anh-dai-dien-dep-chat-5.jpg'],
      ['https://imgt.taimienphi.vn/cf/Images/np/2020/1/3/top-anh-dai-dien-dep-chat-6.jpg'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-anime-dep_104204759.jpg'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-cap-doi-dep_104204984.jpg'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-che-hai_104205084.png'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-chibi_104205184.jpg'],
      ['https://img2.thuthuatphanmem.vn/uploads/2018/11/30/anh-dai-dien-cho-facebook_104205205.jpg'],
    );
    return implode("", $image[array_rand($image)]);
  }

  public static function returnBanking(){
    $message = '<p>NGUYEN VAN A - Ngân hàng MB Bank</p><p>Số tài khoản: 0123456789</p> <br>';
    $message .= '<p>NGUYEN VAN A - Ngân hàng TP Bank</p><p>Số tài khoản: 0123456789</p> <br>';
    $message .= '<p>NGUYEN VAN A - Ngân hàng VP Bank</p><p>Số tài khoản: 0123456789</p> <br>';
    return $message;
  }

  public static function createNotification($user_id, $sender_id, $taget_id, $type, $content){
    if($user_id == $sender_id){
      return;
    }else{
      $notification = new Notification();
      $notification->user_id = $user_id;
      $notification->sender_id = $sender_id;
      $notification->taget_id = $taget_id;
      $notification->type = $type;
      $notification->content = $content;
      $notification->save();
      broadcast(new NotificationPosted($user_id, $notification))->toOthers();
      return $notification;
    }
    
  }
}
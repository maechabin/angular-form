<?php
class Sendmail {

  const TO = "as-question@moba8.net";
  const HEADER = "from: as-question@moba8.net\nbcc: t_maeda@fancs.com";
  private $data;
  private $message;
  private $subject = "特単・商品提供希望受付";

  private function get_data() {

    $date = date("Y/n/j H:i:s");
    $asid = (isset($_GET["asid"]) && $_GET["asid"] != "") ? $_GET["asid"] : "---";
    $email = (isset($_GET["email"]) && $_GET["email"] != "") ? $_GET["email"] : "---";
    $siteName = (isset($_GET["siteName"]) && $_GET["siteName"] != "") ? $_GET["siteName"] : "---";
    $url = (isset($_GET["url"]) && $_GET["url"] != "") ? $_GET["url"] : "---";
    $inquiry = (isset($_GET["inquiry"]) && $_GET["inquiry"] != "") ? $_GET["inquiry"] : "---";
    $pid = (isset($_GET["pid"]) && $_GET["pid"] != "") ? $_GET["pid"] : "---";
    $pname = (isset($_GET["pname"]) && $_GET["pname"] != "") ? $_GET["pname"] : "---";
    $ip_address = $_SERVER["REMOTE_ADDR"];

    $this->data = array($date, $asid, $email, $siteName, $url, $inquiry, $pid, $pname, $ip_address);

  }

  private function create_message() {

    $this->message .= "\n"
    . "特単・商品提供希望受付\n"
    . "\n"
    . "----- 【受付内容】 -----\n"
    . "受付日時: " . $this->data[0] . "\n"
    . "IPアドレス: " . $this->data[8] . "\n"
    . "\n"
    . "対象PG: " . $this->data[7] . "\n"
    . "対象PID: " . $this->data[6] . "\n"
    . "\n"
    . "ASID: " . $this->data[1] . "\n"
    . "メールアドレス: " . $this->data[2] . "\n"
    . "サイト名: " . $this->data[3] . "\n"
    . "サイトURL: " . $this->data[4] . "\n"
    . "備考: " . $this->data[5] . "\n";

  }

  private function send_mail() {

    mb_language("japanese");
    mb_internal_encoding("utf-8");
    mb_send_mail(
      self::TO,
      $this->subject,
      $this->message,
      self::HEADER
    );

  }

  public function init() {
    $this->get_data();
    $this->create_message();
    $this->send_mail();
    echo $this->message;
  }

}

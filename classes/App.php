<?php
  namespace core;

  class App
  {
    private $_conf = [];
    public static function run ($config)
    {
      $_conf = $config;
      $req = new Request ();
      $resp = new Response ();

      $r = $req->parse();
      echo $resp ->response($r);

      /*echo $resp->response($req->parse());*/
    }
  }

?>
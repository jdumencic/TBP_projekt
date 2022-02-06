<?php

class Baza
{
   const host        = "host = 127.0.0.1";
   const port        = "port = 5432";
   const ime_baze      = "dbname = " . "postgres";
   const korisnik = "user = postgres password=josipa";

   const connectionString = self::host . " " . self::port . " " . self::ime_baze . " " . self::korisnik;

   public static function upit($sql)
   {
      $db = pg_connect(self::connectionString);

      if (!$db) {
         echo json_encode(["status" => 500, "message" => "Internal server error"]);
         pg_close($db);
         die();
      }


      $ret = @pg_query($db, $sql);

      if(!$ret)
       {
        $greska = pg_last_error($db);
      
        $tekstGreske = substr($greska, 7, strpos($greska, 'CONTEXT:', 7) - 8);

        pg_close($db);

        return $tekstGreske;
      }
      
      pg_close($db);

      return true;
   }

   public static function select($sql)
   {
      $db = pg_connect(self::connectionString);

      if (!$db) {
         echo json_encode(["status" => 500, "message" => "Internal server error"]);
         pg_close($db);
         die();
      }

      $ret = pg_query($db, $sql);

      if (!$ret) {
         echo json_encode(["status" => 500, "message" => "Bad request"]);
         pg_close($db);
         die();
      }

      $izlaz = [];

      while ($row = pg_fetch_object($ret)) {
        $izlaz = [...$izlaz, $row];
      }

      pg_close($db);

      return $izlaz;
   }
}

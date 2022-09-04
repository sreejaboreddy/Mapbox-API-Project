<?php

   require_once __DIR__ . '/MapboxRequestHandler.php';
   require_once __DIR__ . '/MapboxResponseHandler.php';

   $request = $_REQUEST;

   $wrapperFunction = new MapboxRequestHandler($request);

   $queryUrl= $wrapperFunction->process();

   $query = $wrapperFunction->getQuery();

   $error = $wrapperFunction->getError();

   $errorCode = $wrapperFunction->getErrorCode();

   $responseFunction = new MapboxResponseHandler();
   
   $response = $responseFunction->mainProcess($queryUrl , $query , $error ,$errorCode);
  
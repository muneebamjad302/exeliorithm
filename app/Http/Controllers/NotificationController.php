<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Expr\Print_;

// use Illuminate\Support\Arr;

class NotificationController extends Controller
{
   public function activityNotifications(Request $request)
   {
      // return $request->transaction_data;
      $request->validate([
         'numAndDays'=>"required",
         'transactionAmount'=>"required",
      ]);
      $numbers = $request->transactionAmount;
      $trail_days = $request->numAndDays;
      $trail_days_count = $request->numAndDays;
      $notifications = 0;

      if (count($numbers) == 0 || count($numbers) < $trail_days) {
         return 'Median of an empty array is undefined';
      }

      for ($i=0; $trail_days_count <= count($numbers) ; $i++) { 
        

         $notification_array = array_slice($request->transactionAmount,$i,$trail_days);
         // return $notification_array;
         $exp=count($notification_array);
         $expenditure = $numbers[++$exp];

         if ($exp == 0) {
           return 'Median of an empty array is undefined';
         }
      
         $middle_index = floor($exp / 2);
         sort($notification_array, SORT_NUMERIC);
         $median = $notification_array[$middle_index]; // assume an odd # of items

         // Handle the even case by averaging the middle 2 items
         if ($exp % 2 == 0) {
           $median = ($median + $notification_array[$middle_index - 1]) / 2;
         }

         if ($expenditure >= 2*$median) {
            $notifications++; 
         }

         $trail_days_count++;
      }

      return $notifications;
   }
}

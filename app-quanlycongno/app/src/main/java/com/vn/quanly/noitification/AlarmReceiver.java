package com.vn.quanly.noitification;

import android.annotation.SuppressLint;
import android.app.ActivityManager;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;

import androidx.annotation.RequiresApi;

import com.vn.quanly.utils.SaveDataSHP;


public class AlarmReceiver extends BroadcastReceiver {

    @RequiresApi(api = Build.VERSION_CODES.O)
    @SuppressLint("LongLogTag")
    @Override
    public void onReceive(Context context, Intent intent) {
       // Log.e("BroadcastReceiver","call BroadcastReceiver");
        String token = new SaveDataSHP(context).getShpToken();
        Intent newIntent = new Intent(context, MyService.class);
        Bundle mBundle = new Bundle();
        mBundle.putString("token", token);
        newIntent.putExtras(mBundle);
     ///   context.startService(newIntent);
        try {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
                context.startForegroundService(newIntent);
            } else {
                context.startService(newIntent);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
//    private boolean isMyServiceRunning(Class<?> serviceClass,Context context) {
//        ActivityManager manager = (ActivityManager)  context.getSystemService(Context.ACTIVITY_SERVICE);
//        for (ActivityManager.RunningServiceInfo service : manager.getRunningServices(Integer.MAX_VALUE)) {
//            if (serviceClass.getName().equals(service.service.getClassName())) {
//                return true;
//            }
//        }
//        return false;
//    }
}

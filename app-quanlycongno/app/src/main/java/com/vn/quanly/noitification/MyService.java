package com.vn.quanly.noitification;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.Service;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.IBinder;
import android.util.Log;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.core.app.NotificationCompat;
import androidx.core.app.NotificationManagerCompat;
import androidx.core.app.ServiceCompat;

import com.vn.quanly.R;
import com.vn.quanly.api.getAPI;
import com.vn.quanly.utils.ConfigAPI;
import com.vn.quanly.utils.ToolsCheck;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.ProtocolException;
import java.net.URL;
import java.text.DecimalFormat;
import java.text.NumberFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

import static com.vn.quanly.noitification.NotificationUI.CHANNEL_1;

public class MyService extends Service {
    private NotificationManagerCompat notificationManagerCompat;
    NotificationManager notificationManager;

    SimpleDateFormat sdf;
    private  String token;
    Locale localeVN = new Locale("vi", "VN");
    NumberFormat currencyVN = NumberFormat.getCurrencyInstance(localeVN);

    @Override
    public void onCreate() {
        super.onCreate();
        sdf = new SimpleDateFormat("yyyy-MM-dd");
        notificationManagerCompat = NotificationManagerCompat.from(this);
         notificationManager =
                (NotificationManager) getSystemService(NOTIFICATION_SERVICE);
         //startForeground(1, new Notification());
    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
        Log.e("Service","Noitification");
        token = intent.getStringExtra("token");

        new getNotification(token).execute();
        return START_NOT_STICKY;
    }

    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }

    public void setNotification(int id,String title,String description,String bigText){
        Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
//        NotificationManager notificationManager = (NotificationManager) getSystemService(getApplicationContext().NOTIFICATION_SERVICE);
//        CharSequence name = "MyStreamingApplication";
        Notification notification =new NotificationCompat.Builder(this, NotificationUI.CHANNEL_1)
                .setSmallIcon(R.drawable.logo2)
                .setContentTitle(title)
                .setContentText(description)
                .setPriority(NotificationCompat.PRIORITY_HIGH)
                .setCategory(NotificationCompat.CATEGORY_MESSAGE)
                .setStyle(new NotificationCompat.BigTextStyle()
                        .bigText(bigText))
                .setSound(alarmSound)
                .build();

        //notificationManager.notify(id,notification);
        id = (int) System.currentTimeMillis();
        //Log.e("index",Integer.toString(id));
        ServiceCompat.stopForeground(this, ServiceCompat.STOP_FOREGROUND_DETACH);
        startForeground(id,notification);
    }


    @Override
    public boolean onUnbind(Intent intent) {
        Log.e("service","onUnbind");
        return super.onUnbind(intent);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        Log.e("service","onDestroy");

    }

    class getNotification extends AsyncTask<String ,String,Void>{
        String token;
        String response="";
        final String today = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).format(new Date());
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        Date timenow ;
        public  getNotification(String token){
            this.token = token;
        }
        @Override
        protected Void doInBackground(String... strings) {
            URL url = null;
            try {
                url = new URL(ConfigAPI.API_CLIENT);
            } catch (MalformedURLException e1) {
                e1.printStackTrace();
            }
            HttpURLConnection urlConnection = null;
            try {
                urlConnection = (HttpURLConnection) url.openConnection();
            } catch (IOException e) {
                e.printStackTrace();
            }
            try {
                urlConnection.setRequestMethod("GET");
            } catch (ProtocolException e) {
                e.printStackTrace();
            }
            urlConnection.setRequestProperty("content-type","application/json");
            urlConnection.setConnectTimeout(6000);
            urlConnection.setUseCaches(false);
            urlConnection.setRequestProperty("Authorization", "Bearer " + token);
            InputStream inputStream = null;

            // get stream
            try {
                if (urlConnection.getResponseCode() < HttpURLConnection.HTTP_BAD_REQUEST) {
                    inputStream = urlConnection.getInputStream();
                } else {
                    inputStream = urlConnection.getErrorStream();
                }
            } catch (IOException e) {
                e.printStackTrace();
            }
            // parse stream
            BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(inputStream));
            String temp="";
            while (true) {
                try {
                    if (!((temp = bufferedReader.readLine()) != null)) break;
                } catch (IOException e) {
                    e.printStackTrace();
                }
                response += temp;
            }
            return null;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            try {
                timenow = format.parse(today);
            } catch (ParseException e) {
                e.printStackTrace();
            }
        }

        @RequiresApi(api = Build.VERSION_CODES.O)
        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            int index = 1;
            DecimalFormat formatter = new DecimalFormat("###,###,###");
            final String today = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).format(new Date());
            try {
                JSONArray rs =  new JSONArray(response);
                for (int i = 0;i<rs.length();i++){
                    JSONObject client = new JSONObject(rs.get(i).toString());
                    if(!client.getString("status").equals("resolved")){
                        ///Xử lý khi quá số tiền
                        if(!client.getString("money_limit").equals("null")) {
                            Double money_limit = Double.parseDouble(client.getString("money_limit"));
                            Double total = Double.parseDouble(client.getString("total"));
                            if (money_limit <= total) {
                                setNotification(index, client.getString("code"), "Nợ " + currencyVN.format(Double.parseDouble(client.getString("total"))), "");
                                try {
                                    Thread.sleep(1000);
                                } catch (InterruptedException e) {
                                    e.printStackTrace();
                                }
                                index++;
                            }
                        }
                            // xử lý thông báo tiền khi quá ngày
                        if(!client.getString("date_limit").equals("null")){
                            try {
                                Date date_limit = format.parse(client.getString("date_limit"));
                                if(date_limit.before(timenow)|| date_limit.equals(timenow)){
                                    setNotification(index,client.getString("code"),"Quá ngày nợ " +currencyVN.format(Double.parseDouble(client.getString("total"))),"");

                                    index++;
                                }
                            } catch (ParseException e) {
                                e.printStackTrace();
                            }

                        }
                             //end
                    }
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }
}

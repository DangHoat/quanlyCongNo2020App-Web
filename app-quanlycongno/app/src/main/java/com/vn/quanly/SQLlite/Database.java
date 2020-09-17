package com.vn.quanly.SQLlite;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.os.Build;
import android.provider.ContactsContract;
import android.util.Log;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;

import com.vn.quanly.model.Client;

import java.sql.SQLClientInfoException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Locale;

public class Database extends SQLiteOpenHelper {
    public Database(@Nullable Context context) {
        super(context, ScripDatabase.DATABASE_NAME, null, ScripDatabase.DATABASE_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        db.execSQL(ScripDatabase.script_create);
        db.execSQL(ScripDatabase.script_cate);
        db.execSQL(ScripDatabase.script_type);
        db.execSQL(ScripDatabase.script_unit);
        db.execSQL("INSERT INTO TYPE (TYPE) VALUES('trắng nhân tạo'),('kim sa trung'),('trắng vân mây');");
        db.execSQL("INSERT INTO CATE (CATE) VALUES('bàn bếp'),('lavabo'),('cầu thang');");
        db.execSQL("INSERT INTO UNIT (UNIT) VALUES('cái'),('m2'),('cái');");
        Log.e("onCreate","onCreate");
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL(ScripDatabase.scrips_drop);
        onCreate(db);
    }

    @RequiresApi(api = Build.VERSION_CODES.O)
    public void  AddNote(Context context, Client note){
        try {
            SQLiteDatabase sqLiteDatabase = this.getWritableDatabase();
            ContentValues values = new ContentValues();
            values.put(ScripDatabase.COLUMN_NOTE_NAME,note.getName());
            values.put(ScripDatabase.COLUMN_NOTE_ADDRESS,note.getAddress());
            values.put(ScripDatabase.COLUMN_NOTE_TELEPHONE,note.getTelecom());
            values.put(ScripDatabase.COLUMN_NOTE_CODE,note.getCode());
            values.put(ScripDatabase.COLUMN_NOTE_TOTAL,note.getTotal());
            values.put(ScripDatabase.COLUMN_NOTE_NOTE,note.getNote());
            final Long check = sqLiteDatabase.insert(ScripDatabase.TABLE_NAME_NOTE,null,values);
            sqLiteDatabase.close();

        }catch (Exception e) {
            e.printStackTrace();
        }

    }

    public void dropTable(){
        SQLiteDatabase sqLiteDatabase = this.getWritableDatabase();
    }
    public List<Client> getAllNode(){
        List<Client> clients = new ArrayList<>();
        SQLiteDatabase sqLiteDatabase = this.getReadableDatabase();
        Cursor cursor = sqLiteDatabase.rawQuery(ScripDatabase.selectQuery,null);
        if(cursor.moveToFirst()){
            do {
                Client client = new Client(
                        (cursor.getInt(0)),
                        (cursor.getString(1)),
                        (cursor.getString(2)),
                        (cursor.getString(3)),
                        (cursor.getString(4)),
                        (cursor.getString(5)),
                        (cursor.getString(6))
                        );
                clients.add(client);
            }while (cursor.moveToNext());
        }
        cursor.close();
        sqLiteDatabase.close();
        return clients;
    }

    /**
     * funtion DeleteNote
     * @param CODE
     */
    public void DeleteNote(String CODE){
        SQLiteDatabase db = this.getWritableDatabase();
        db.delete(ScripDatabase.TABLE_NAME_NOTE, ScripDatabase.COLUMN_NOTE_CODE+ " = ?",
                new String[] { String.valueOf(CODE)});
        db.close();
    }
    public void clearAll(){
        SQLiteDatabase db = this.getWritableDatabase();
        db.execSQL(ScripDatabase.scrips_delete_table);
    }

    /**
     *
     * @param table
     * @param colum
     * @param data
     */
    public void addOptions( String table,String colum, String data){
        try {
            SQLiteDatabase sqLiteDatabase = this.getWritableDatabase();
            ContentValues values = new ContentValues();
            values.put(colum,data);
            sqLiteDatabase.insert(table,null,values);
            sqLiteDatabase.close();

        }catch (Exception e) {
            e.printStackTrace();
        }

    }
    public void deleteOptions(String table,String colum, String data){
        SQLiteDatabase db = this.getWritableDatabase();
        db.delete(table, colum+ " = ?",
                new String[] { String.valueOf(data)});
        db.close();
    }

    public List<String> getAllOptions(String table){
        List<String > data = new ArrayList<>();
        SQLiteDatabase sqLiteDatabase = this.getReadableDatabase();
        Cursor cursor = sqLiteDatabase.rawQuery("SELECT  * FROM " + table,null);
        if(cursor.moveToFirst()){
            do{
            data.add(cursor.getString(1));
            }while (cursor.moveToNext());
        }
        return data;
    }
}

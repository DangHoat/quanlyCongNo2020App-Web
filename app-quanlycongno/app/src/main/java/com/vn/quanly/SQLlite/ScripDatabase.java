package com.vn.quanly.SQLlite;

public class ScripDatabase {
    protected static final String TAG = "SQLite";
    // Phiên bản
    protected static final int DATABASE_VERSION = 1;


    // Tên database
    protected static final String DATABASE_NAME = "SQLite_QLCN";


    // Tên bảng: Note.
    protected static final String TABLE_NAME_NOTE = "Note";
    protected static final String TABLE_CATE = "CATE";
    protected static final String TABLE_TYPE = "TYPE";
    protected static final String TABLE_UNIT = "UNIT";
    protected static final String COLUMN_NOTE_CODE ="Code";
    protected static final String COLUMN_NOTE_NAME ="name";
    protected static final String COLUMN_NOTE_ADDRESS = "Adress";
    protected static final String COLUMN_NOTE_TELEPHONE ="telecom";
    protected static final String COLUMN_NOTE_TOTAL ="total";
    protected static final String COLUMN_NOTE_NOTE ="note";
    protected static final String script_create = "CREATE TABLE " + TABLE_NAME_NOTE + "("

            + "ID"+ " INTEGER PRIMARY KEY,"
            + COLUMN_NOTE_CODE+ " TEXT,"
            + COLUMN_NOTE_NAME + " TEXT,"
            + COLUMN_NOTE_ADDRESS + " TEXT,"
            + COLUMN_NOTE_TELEPHONE + " TEXT,"
            + COLUMN_NOTE_TOTAL + " TEXT,"
            + COLUMN_NOTE_NOTE + " TEXT"+ ")";
    protected static final String script_cate = " CREATE TABLE " + TABLE_CATE+ "(ID INTEGER PRIMARY KEY, CATE TEXT )";
    protected static final String script_type = " CREATE TABLE " + TABLE_TYPE+ " (ID INTEGER PRIMARY KEY, TYPE TEXT )";
    protected static final String script_unit = " CREATE TABLE " + TABLE_UNIT+ " (ID INTEGER PRIMARY KEY,UNIT TEXT )";

    public static final String scrips_drop = "DROP TABLE IF EXISTS " + TABLE_NAME_NOTE;
    public static final String scrips_delete_table = "DELETE FROM " + TABLE_NAME_NOTE;

    public static  final String selectQuery = "SELECT  * FROM " + TABLE_NAME_NOTE;
}

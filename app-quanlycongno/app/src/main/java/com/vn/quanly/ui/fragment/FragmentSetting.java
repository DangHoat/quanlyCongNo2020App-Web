package com.vn.quanly.ui.fragment;

import android.content.Context;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.fragment.app.Fragment;

import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;
import com.vn.quanly.R;
import com.vn.quanly.SQLlite.Database;
import com.vn.quanly.utils.SaveDataSHP;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.List;

public class FragmentSetting extends Fragment {
    TimePicker timePicker;
    Button btnAcceptTime;
    Calendar calendar;
    SaveDataSHP saveDataSHP;
    EditText edSolan;
    reCallAlarm reCallAlarm;
    ChipGroup groupCate;
    ChipGroup groupType;
    ChipGroup groupUnit;

    Button btnAddCate;
    EditText addCate;

    Button btnAddType;
    EditText addType;
    Button btnAddUnit;
    EditText edAddUnit;
    Database database;

    TextView tvTypeName;
    private int [] time;
    @RequiresApi(api = Build.VERSION_CODES.M)
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_setting,container,false);
        Init(view);
        database = new Database(getContext());
        Log.e("cate",database.getAllOptions("TYPE").toString());
        saveDataSHP =new SaveDataSHP(getContext());
        time = saveDataSHP.getTime();
        SetDefault (time);
        GetTime();
        ListChip();
        btnAddCate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String newCate = addCate.getText().toString();
                List<String> cate = database.getAllOptions("CATE");
                if(newCate.equals("")){
                    addCate.setError("Không để trống");
                    addCate.requestFocus();
                    return;
                }
                if (!cate.contains(newCate)) {
                    groupCate.removeAllViews();
                    database.addOptions("CATE","CATE",newCate);
                    ShowChipGroup(groupCate,database.getAllOptions("CATE"),"CATE");
                    addCate.setText("");
                    return;
                }
                addCate.setText("");
                Toast.makeText(getContext(),"Đã Tồn Tại Tên Hàng Hóa ",Toast.LENGTH_SHORT).show();
            }
        });
        btnAddType.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String newType = addType.getText().toString();
                List<String> type = database.getAllOptions("TYPE");
                if(newType.equals("")){
                    addType.setError("Không để trống");
                    addType.requestFocus();
                    return;
                }
                if (!type.contains(newType)) {
                    groupType.removeAllViews();
                    database.addOptions("TYPE","TYPE",newType);
                    ShowChipGroup(groupType,database.getAllOptions("TYPE"),"TYPE");
                    addType.setText("");
                    return;

                }
                addType.setText("");
                Toast.makeText(getContext(),"Đã Tồn Tại Loại Hàng Hóa ",Toast.LENGTH_SHORT).show();

            }
        });
        btnAddUnit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String newUnit = edAddUnit.getText().toString();
                List<String> unit =  database.getAllOptions("UNIT");
                if(newUnit.equals("")){
                    edAddUnit.setError("Không để trống");
                    edAddUnit.requestFocus();
                    return;
                }
                if(!unit.contains(newUnit)){
                    groupUnit.removeAllViews();
                    database.addOptions("UNIT","UNIT",newUnit);
                    ShowChipGroup(groupUnit,database.getAllOptions("UNIT"),"UNIT");
                    edAddUnit.setText("");
                    return;
                }
                edAddUnit.setText("");
               Toast.makeText(getContext(),"Đã Tồn Tại Đơn Vị Tính",Toast.LENGTH_SHORT).show();

            }
        });
        return view;
    }


    private void ListChip() {
        groupCate.removeAllViews();
        ShowChipGroup(groupCate,database.getAllOptions("CATE"),"CATE");
        groupType.removeAllViews();
        ShowChipGroup(groupType,database.getAllOptions("TYPE"),"TYPE");
        groupUnit.removeAllViews();
        ShowChipGroup(groupUnit,database.getAllOptions("UNIT"),"UNIT");

    }
//    private void GroupCate(String value){
//        LayoutInflater inflater = LayoutInflater.from(getContext());
//        ArrayList<String> titles = ChipChip(value);
//        for (final String title : titles){
//            final Chip chip = (Chip) inflater.inflate(R.layout.item_chip,null,false);
//            chip.setText(title);
//            chip.setOnCloseIconClickListener(new View.OnClickListener() {
//                @Override
//                public void onClick(View v) {
//                    groupCate.removeView(v);
//                    addType.setVisibility(View.GONE);
//                    btnAddType.setVisibility(View.GONE);
//                    groupType.setVisibility(View.GONE);
//                    tvTypeName.setVisibility(View.INVISIBLE);
//                    StringBuilder stringBuilder = new StringBuilder("");
//                    for (int i = 0;i<groupCate.getChildCount();i++){
//                        Chip thisChip = (Chip)groupCate.getChildAt(i);
//                            if(i<groupCate.getChildCount()-1){
//                                stringBuilder.append(thisChip.getText()).append(",");
//                            }else {
//                                stringBuilder.append(thisChip.getText());
//                                Log.e("setListCate", stringBuilder.toString());
//                                new SaveDataSHP(getContext()).setListCate(stringBuilder.toString());
//                            }
//                    }
//                }
//            });
//            chip.setOnClickListener(new View.OnClickListener() {
//                @Override
//                public void onClick(View v) {
//                    addType.setVisibility(View.VISIBLE);
//                    btnAddType.setVisibility(View.VISIBLE);
//                    thisCate = chip.getText().toString();
//                    groupType.removeAllViews();
//                    tvTypeName.setVisibility(View.VISIBLE);
//                    tvTypeName.setText(thisCate.toString());
//                    ShowType(chip.getText().toString());
//                }
//            });
//            groupCate.addView(chip);
//        }
//
//    }
    private ArrayList<String> ChipChip(String q){
        String [] chips = q.split(",");
        ArrayList<String> arrayList = new ArrayList<String>(Arrays.asList(chips));
        ArrayList<String> result = new ArrayList<String>();
        for(String a:arrayList ){
            if(!result.contains(a) && !a.trim().equals("")){
              result.add(a);
            }
        }
        return result;

    }
    protected void ShowChipGroup(final ChipGroup chipGroup, List<String> titles, final String table){
        LayoutInflater inflater = LayoutInflater.from(getContext());
        for (final String title : titles){
            final Chip chip = (Chip) inflater.inflate(R.layout.item_chip,null,false);
            chip.setText(title);
            chip.setOnCloseIconClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    chipGroup.removeView(v);
                    String chipName = chip.getText().toString();
                    database.deleteOptions(table,table,chipName);
                }
            });
            chip.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {

                }
            });
            chipGroup.addView(chip);
        }
    }



    @RequiresApi(api = Build.VERSION_CODES.M)
    private void SetDefault(int [] time) {
        timePicker.setHour(time[0]);
        timePicker.setMinute(time[1]);
        edSolan.setText(Integer.toString(time[2]));
    }

    private void Init(View view){
        saveDataSHP = new SaveDataSHP(getContext());
        timePicker = view.findViewById(R.id.timePicker);
        timePicker.setIs24HourView(true);
        btnAcceptTime = view.findViewById(R.id.btnAcceptTime);
        edSolan = view.findViewById(R.id.edSolan);
        groupCate = (ChipGroup) view.findViewById(R.id.groupCate);
        groupType = (ChipGroup) view.findViewById(R.id.groupType);
        btnAddCate =  view.findViewById(R.id.btnAddCate);
        addCate =  view.findViewById(R.id.addCate);
        btnAddType =  view.findViewById(R.id.btnAddType);
        addType =  view.findViewById(R.id.addType);
        tvTypeName = view.findViewById(R.id.tvTypeName);
        groupUnit = view.findViewById(R.id.groupUnit);
        btnAddUnit = view.findViewById(R.id.btnAddUnit);
        edAddUnit = view.findViewById(R.id.edAddUnit);
        calendar = Calendar.getInstance();
    }
    private void GetTime(){
        btnAcceptTime.setOnClickListener(new View.OnClickListener() {
            @RequiresApi(api = Build.VERSION_CODES.M)
            @Override
            public void onClick(View v) {
                calendar.set(Calendar.HOUR_OF_DAY,timePicker.getCurrentHour());
                calendar.set(Calendar.MINUTE,timePicker.getMinute());
                int hour = timePicker.getHour();
                int min = timePicker.getMinute();
                int times = Integer.parseInt(edSolan.getText().toString().trim());
                if(time[0]!=hour || time[1]!=min||time[2]!= times ) {
                    saveDataSHP.setTime(hour,min,times);
                    Toast.makeText(getContext(),"Thay đổi thành công",Toast.LENGTH_SHORT).show();
                    reCallAlarm.reCallInMain();
                }
            }
        });
    }
    public  interface reCallAlarm{
        void reCallInMain();
    }
    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
        reCallAlarm =(reCallAlarm)context;
    }

}

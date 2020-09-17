package com.vn.quanly.adapter;

import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.ImageButton;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.vn.quanly.R;
import com.vn.quanly.adapter.Interface.recycleViewAction;
import com.vn.quanly.api.AsyntaskAPI;
import com.vn.quanly.model.PayBook;
import com.vn.quanly.ui.ClientActivity;
import com.vn.quanly.utils.ConfigAPI;
import com.vn.quanly.utils.SaveDataSHP;
import com.vn.quanly.utils.ToolsCheck;
import com.vn.quanly.utils.VNCharacterUtils;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class ItemPayBooks extends RecyclerView.Adapter<RecyclerView.ViewHolder>  implements Filterable {
    private ArrayList<PayBook> payBooks;
    private ArrayList<PayBook> payBooksFull;
    private Context context;
    Locale localeVN = new Locale("vi", "VN");
    NumberFormat currencyVN = NumberFormat.getCurrencyInstance(localeVN);


    public ItemPayBooks(Context context, ArrayList<PayBook> payBooks) {
        this.context = context;
        this.payBooks = payBooks;
        this.payBooksFull = payBooks;
        DecimalFormatSymbols decimalFormatSymbols = ((DecimalFormat) currencyVN).getDecimalFormatSymbols();
        decimalFormatSymbols.setCurrencySymbol("");
        ((DecimalFormat) currencyVN).setDecimalFormatSymbols(decimalFormatSymbols);
    }

    @NonNull
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_paybook, parent, false);
        return new ItemPayBookView(view);
    }

    @Override
    public void onBindViewHolder(@NonNull RecyclerView.ViewHolder holder, final int position) {
        final PayBook pay = payBooks.get(position);
        ((ItemPayBookView) holder).customer.setText(pay.getCustomer());
        ((ItemPayBookView) holder).code.setText(pay.getCodeClient());
        if (ToolsCheck.isNumeric(pay.getTotal())) {
            ((ItemPayBookView) holder).money.setText(currencyVN.format(Long.parseLong(pay.getTotal())));
        } else {
            ((ItemPayBookView) holder).money.setText(currencyVN.format(Long.parseLong("0")));
        }
        ((ItemPayBookView) holder).btnShow.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                gotoCustom(pay.getCodeClient());
            }
        });
    }

    @Override
    public int getItemCount() {
        return payBooks.size();
    }

    @Override
    public Filter getFilter() {
        return dataFilter;
    }
    private Filter dataFilter = new Filter() {
        @Override
        protected FilterResults performFiltering(CharSequence constraint) {
            List<PayBook> list = new ArrayList<>();
            if(constraint.toString().trim().isEmpty()){
                list.addAll(payBooksFull);
//                notifyDataSetChanged();
            }else {
                String textFilter = VNCharacterUtils.removeAccent(constraint.toString().toLowerCase().trim());
                List<PayBook> filteredList = new ArrayList<>();
                for (PayBook item : payBooksFull){
                    if(VNCharacterUtils.removeAccent(item.getCodeClient().toLowerCase().trim()).contains(textFilter)){
                        filteredList.add(item);
                    }
                }
                list = filteredList;
            }
            FilterResults results = new FilterResults();
            results.values = list;
            return results;
        }

        @Override
        protected void publishResults(CharSequence constraint, FilterResults results) {
//            payBooksFilter.clear();
//            if((List)results.values!=null){
//                payBooksFilter.addAll((List)results.values);
//            }
            payBooks = (ArrayList<PayBook>) results.values;
            notifyDataSetChanged();
        }
    };

    private class ItemPayBookView extends RecyclerView.ViewHolder {
        TextView customer;
        TextView money;
        TextView code;
        ImageButton btnShow;

        public ItemPayBookView(@NonNull View itemView) {
            super(itemView);
            customer = itemView.findViewById(R.id.customer);
            code = itemView.findViewById(R.id.code);
            money = itemView.findViewById(R.id.timedate);
            btnShow = itemView.findViewById(R.id.btnShow);
        }
    }

    public void gotoCustom(String client) {
        JSONObject data = new JSONObject();
        try {
            data.put("code", client);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        AsyntaskAPI getClient = new AsyntaskAPI(context, data, ConfigAPI.API_CLIENT_BY_CODE, "POST", new SaveDataSHP(context).getShpToken()) {
            @Override
            public void setOnPreExcute() {

            }

            @Override
            public void setOnPostExcute(String JsonResult) {
                try {
                    JSONObject rs = new JSONObject(JsonResult);
                    if (!rs.toString().equals("")) {
                        if (rs.getString("message").equals("Successfully") && !rs.getString("info").equals("")) {
                            JSONObject info = new JSONObject(rs.getString("info"));
                            new SaveDataSHP(context).setClient(info);
                            Intent intent = new Intent(context, ClientActivity.class);
                            context.startActivities(new Intent[]{intent});
                        } else {
                            Toast.makeText(context, "Lỗi khách hàng!", Toast.LENGTH_SHORT).show();
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        };
        getClient.execute();
    }
}

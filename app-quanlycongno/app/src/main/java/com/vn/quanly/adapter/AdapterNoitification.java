package com.vn.quanly.adapter;

import android.app.AlertDialog;
import android.app.DatePickerDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.DatePicker;
import android.widget.ImageView;
import android.widget.PopupMenu;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.constraintlayout.widget.ConstraintLayout;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.vn.quanly.R;
import com.vn.quanly.adapter.Interface.recycleViewAction;
import com.vn.quanly.api.AsyntaskAPI;
import com.vn.quanly.model.Noitification;
import com.vn.quanly.utils.ConfigAPI;
import com.vn.quanly.utils.SaveDataSHP;
import com.vn.quanly.utils.ToolsCheck;


import org.json.JSONException;
import org.json.JSONObject;

import java.text.NumberFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import de.hdodenhof.circleimageview.CircleImageView;

public class AdapterNoitification extends RecyclerView.Adapter<RecyclerView.ViewHolder> {
    private final int VIEW_TYPE_ITEM = 0;
    private final int VIEW_TYPE_LOADING = 1;
    private boolean isLoading;
    private List<Noitification> noitificationList;
    private RecyclerView recyclerView;
    private recycleViewAction recycleViewAction;
    private Context context;
    int totalItemCount, lastVisibleItem;
    private PopupMenu popup;
    SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
    NumberFormat currencyVN = NumberFormat.getCurrencyInstance(new Locale("vi", "VN"));

    public AdapterNoitification(Context context, RecyclerView recyclerView, List<Noitification> noitificationList) {
        this.context = context;
        this.noitificationList = noitificationList;


        final LinearLayoutManager linearLayoutManager = (LinearLayoutManager) recyclerView.getLayoutManager();
        recyclerView.addOnScrollListener(new RecyclerView.OnScrollListener() {
            @Override
            public void onScrolled(@NonNull RecyclerView recyclerView, int dx, int dy) {
                super.onScrolled(recyclerView, dx, dy);
                totalItemCount = linearLayoutManager.getItemCount();
                lastVisibleItem = linearLayoutManager.findLastVisibleItemPosition();
                if (!isLoading && totalItemCount < lastVisibleItem) {
                    if (recycleViewAction != null) {
                        recycleViewAction.loadMore();
                    }
                    isLoading = true;
                }
            }
        });
    }

    @NonNull
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        switch (viewType) {
            case VIEW_TYPE_ITEM: {
                View view = LayoutInflater.from(context).inflate(R.layout.item_notification, parent, false);
                return new AdapterNoitification.ItemNoitificationView(view);
            }
            case VIEW_TYPE_LOADING: {
                View view = LayoutInflater.from(context).inflate(R.layout.item_loading, parent, false);
                return new AdapterNoitification.LoadingView(view);
            }
        }
        return null;
    }


    @Override
    public void onBindViewHolder(@NonNull final RecyclerView.ViewHolder holder, final int position) {
        if (holder instanceof AdapterNoitification.ItemNoitificationView) {
            final Noitification noitification = noitificationList.get(position);
            ((ItemNoitificationView) holder).tvTenHang.setText(noitification.getCode() + " - " + currencyVN.format(Double.parseDouble(noitification.getMoney())));
            if (!noitification.getCheck()) {
                ((ItemNoitificationView) holder).layout.setBackground(ContextCompat.getDrawable(context, R.drawable.border_bottom_gray));
            } else {
                ((ItemNoitificationView) holder).layout.setBackground(ContextCompat.getDrawable(context, R.drawable.border_bottom_white));
            }
            if (!new SaveDataSHP(context).getString(SaveDataSHP.SHP_PROMISE).equals("1")) {
                ((ItemNoitificationView) holder).more.setVisibility(View.GONE);
            }
            ((ItemNoitificationView) holder).more.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    popup = new PopupMenu(context, ((ItemNoitificationView) holder).more);
                    popup.getMenuInflater().inflate(R.menu.option_notification, popup.getMenu());
//                    if(noitification.getCheck()){
//                        popup.getMenu().findItem(R.id.watch).setVisible(true);
//                    }
                    popup.setOnMenuItemClickListener(new PopupMenu.OnMenuItemClickListener() {
                        @Override
                        public boolean onMenuItemClick(MenuItem item) {
                            switch (item.getItemId()) {
                                case R.id.hanno:
                                    changeTime(noitification, position);
                                    return false;
                                case R.id.delete:
                                    new AlertDialog.Builder(context)
                                            .setTitle("Thanh Toán Tiền")
                                            .setMessage("Thanh toán cho mã khách hàng " +"\""+ noitification.getCode() + "\" nợ " + currencyVN.format(Double.parseDouble(noitification.getMoney())) + "?")
                                            .setPositiveButton("Đồng ý", new DialogInterface.OnClickListener() {
                                                public void onClick(DialogInterface dialog, int which) {
                                                    Delete(noitification, position);
                                                }
                                            })
                                            .setNegativeButton("Bỏ Qua", null)
                                            .setCancelable(true)
                                            .show();
                                    return false;
                                case R.id.cancel_noitification:
                                    JSONObject oldData = noitification.getData();
                                    try {
                                        oldData.put("date_limit", "2100-01-01");
                                        oldData.put("new_code", noitification.getCode());
                                        if (ToolsCheck.checkInternetConnection(context)) {
                                            callAPI(oldData, position);
                                        }
                                    } catch (JSONException e) {
                                        e.printStackTrace();
                                    }
                                    return false;
                                default:
                                    return false;
                            }
                        }
                    });
                    popup.show();
                }
            });
            ((ItemNoitificationView) holder).tvTime.setText(noitification.getTime());

            if (ToolsCheck.isNumeric(noitification.getMoney())) {
                ((ItemNoitificationView) holder).tvMoney.setText(currencyVN.format(Long.parseLong(noitification.getMoney())));
            } else {
                ((ItemNoitificationView) holder).tvMoney.setText(noitification.getMoney());
            }


        } else if (holder instanceof AdapterNoitification.LoadingView) {
            AdapterNoitification.LoadingView loadingView = (AdapterNoitification.LoadingView) holder;
            loadingView.progress_circular.setIndeterminate(true);
        }
    }

    @Override
    public int getItemCount() {
        return noitificationList == null ? 0 : noitificationList.size();
    }

    @Override
    public int getItemViewType(int position) {
        return noitificationList.get(position) == null ? VIEW_TYPE_LOADING : VIEW_TYPE_ITEM;
    }

    public void setRecycleViewAction(com.vn.quanly.adapter.Interface.recycleViewAction recycleViewAction) {
        this.recycleViewAction = recycleViewAction;
    }

    public void removeItem(int position) {

        if (position >= noitificationList.size())
            return;
        noitificationList.remove(position);
        notifyItemRemoved(position);
    }


    private class LoadingView extends RecyclerView.ViewHolder {
        ProgressBar progress_circular;

        public LoadingView(@NonNull View itemView) {
            super(itemView);
            progress_circular = itemView.findViewById(R.id.progress_circular);
        }
    }

    private class ItemNoitificationView extends RecyclerView.ViewHolder {
        CircleImageView logo;
        TextView tvTime;
        ImageView more;
        TextView tvTenHang;
        TextView tvMoney;
        ConstraintLayout layout;

        public ItemNoitificationView(@NonNull View itemView) {
            super(itemView);
            logo = itemView.findViewById(R.id.icon_noitification);
            tvTime = itemView.findViewById(R.id.tvTime);
            more = itemView.findViewById(R.id.more);
            tvMoney = itemView.findViewById(R.id.tvMoney);
            tvTenHang = itemView.findViewById(R.id.tvDiachi);
            layout = itemView.findViewById(R.id.layout);
        }
    }

    void changeTime(final Noitification noitification, final int position) {
        DatePickerDialog.OnDateSetListener dateSetListener;

        dateSetListener = new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker view, int year, int month, int dayOfMonth) {
                month = month + 1;
                String date = year + "-" + month + "-" + dayOfMonth;
                JSONObject oldData = noitification.getData();
                try {
                    oldData.put("date_limit", date);
                    oldData.put("new_code", noitification.getCode());
                    Log.e("olddata", oldData.toString());
                    if (ToolsCheck.checkInternetConnection(context)) {
                        callAPI(oldData, position);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        };
        Calendar calendar = Calendar.getInstance();
        int year;
        int month;
        int day;
        if (!noitification.getTime().equals("null")) {
            String data_lim[] = noitification.getTime().split("\\-");
            year = Integer.parseInt(data_lim[0]);
            month = Integer.parseInt(data_lim[1]);
            day = Integer.parseInt(data_lim[2]);
        } else {
            year = calendar.get(Calendar.YEAR);
            month = calendar.get(Calendar.MONTH);
            day = calendar.get(Calendar.DAY_OF_MONTH);
        }


        DatePickerDialog dialog = new DatePickerDialog(context,
                android.R.style.Theme_Holo_Light_Dialog_MinWidth, dateSetListener, year, month, day);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        dialog.show();

    }

    void callAPI(final JSONObject data, final int position) {
        AsyntaskAPI changeClient = new AsyntaskAPI(context
                , data, ConfigAPI.API_CLIENT, "PATCH", new SaveDataSHP(context).getShpToken()) {
            @Override
            public void setOnPreExcute() {

            }

            @Override
            public void setOnPostExcute(String JsonResult) {
                Log.e("JsonResult", JsonResult);
                try {
                    JSONObject rs = new JSONObject(JsonResult);
                    if (!rs.toString().equals("") && rs.getString("message").equals("Successfully")) {
                        Toast.makeText(context, "Thay đổi thông tin thành công ", Toast.LENGTH_SHORT).show();
                        Date date1 = format.parse(data.getString("date_limit"));
                        Date date2 = format.parse(new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault()).format(new Date()));
                        if (date1.after(date2)) {
                            removeItem(position);
                            notifyDataSetChanged();
                        }


                    } else {
                        Toast.makeText(context, "Hãy kiểm tra lại", Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException | ParseException e) {
                    e.printStackTrace();
                }

            }
        };
        changeClient.execute();
    }

    void Delete(Noitification noitification, final int position) {

        AsyntaskAPI deleteNoi = new AsyntaskAPI(context, ConfigAPI.API_DELETE_OWE + noitification.getCode(), new SaveDataSHP(context).getShpToken(), false) {
            @Override
            public void setOnPreExcute() {

            }

            @Override
            public void setOnPostExcute(String JsonResult) {
                JSONObject rs = null;
                try {
                    rs = new JSONObject(JsonResult);
                    if (!rs.toString().equals("") && rs.getString("message").equals("Successfully")) {
                        removeItem(position);
                        notifyDataSetChanged();
                        Toast.makeText(context, "Đã xác nhận thanh toán nợ", Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }


            }
        };
        if (ToolsCheck.checkInternetConnection(context)) {
            deleteNoi.execute();
        }
    }
}

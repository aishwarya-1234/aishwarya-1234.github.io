package com.mycaptain.dbmsclassproject.Adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.mycaptain.dbmsclassproject.Model.DoubleModel;
import com.mycaptain.dbmsclassproject.R;
import android.text.Html;
import java.util.List;


public class GroupListViewAdapter extends ArrayAdapter<DoubleModel> {
    private List<DoubleModel> doubleModelList;
    private Context context;

    public GroupListViewAdapter(List<DoubleModel> doubleModelList, Context context) {
        super(context,R.layout.doubleitem_listview,doubleModelList);

        this.doubleModelList = doubleModelList;
        this.context = context;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
        LayoutInflater inflater =LayoutInflater.from(context);
        View listviewitem = inflater.inflate(R.layout.doubleitem_listview,null,true);
        TextView data1 = listviewitem.findViewById(R.id.data1);
        TextView data2 = listviewitem.findViewById(R.id.data2);


        DoubleModel doubleModel = doubleModelList.get(position);
        String data1Str = "<b>Name:&nbsp;&nbsp;&nbsp;&nbsp;</b>"+doubleModel.getData1();
        String data2Str = "<b>Title:&nbsp;&nbsp;&nbsp;&nbsp;</b>"+doubleModel.getData2();
        data1.setText(Html.fromHtml(data1Str));
        data2.setText(Html.fromHtml(data2Str));

        return listviewitem;
    }
}

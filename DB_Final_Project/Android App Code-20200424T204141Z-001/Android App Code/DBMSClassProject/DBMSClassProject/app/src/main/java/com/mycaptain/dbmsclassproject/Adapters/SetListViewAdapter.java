package com.mycaptain.dbmsclassproject.Adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;
import android.graphics.Typeface;
import android.text.Html;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.mycaptain.dbmsclassproject.Model.SingleModel;
import com.mycaptain.dbmsclassproject.R;

import java.util.List;

public class SetListViewAdapter extends ArrayAdapter<SingleModel> {
    private List<SingleModel> singleModelList;
    private Context context;

    public SetListViewAdapter(List<SingleModel> singleModelList, Context context) {
        super(context, R.layout.singleitem_listview,singleModelList);
        this.singleModelList = singleModelList;
        this.context = context;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
        LayoutInflater inflater =LayoutInflater.from(context);
        View listviewitem = inflater.inflate(R.layout.singleitem_listview,null,true);
        TextView name = listviewitem.findViewById(R.id.name);


        SingleModel singleModel = singleModelList.get(position);
        String str="<b> tconst:  </b>"+singleModel.getData();
        name.setText(Html.fromHtml(str));


        return listviewitem;
    }
}


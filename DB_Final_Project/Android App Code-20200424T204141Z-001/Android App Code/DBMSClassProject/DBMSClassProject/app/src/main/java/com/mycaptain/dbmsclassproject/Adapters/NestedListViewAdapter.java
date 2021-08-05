package com.mycaptain.dbmsclassproject.Adapters;

import android.content.Context;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.mycaptain.dbmsclassproject.Model.ExtraModel;
import com.mycaptain.dbmsclassproject.Model.SingleModel;
import com.mycaptain.dbmsclassproject.R;

import java.util.List;

public class NestedListViewAdapter extends ArrayAdapter<ExtraModel> {
    private List<ExtraModel> extraModelList;
    private Context context;

    public NestedListViewAdapter(List<ExtraModel> extraModelList, Context context) {
        super(context, R.layout.custom_layout_listview,extraModelList);
        this.extraModelList = extraModelList;
        this.context = context;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
        LayoutInflater inflater =LayoutInflater.from(context);
        View listviewitem = inflater.inflate(R.layout.custom_layout_listview,null,true);
        TextView ncost = listviewitem.findViewById(R.id.title);
        TextView primaryName = listviewitem.findViewById(R.id.region);
        TextView birthYear = listviewitem.findViewById(R.id.startyear);
        TextView primaryProfession = listviewitem.findViewById(R.id.rating);
        TextView knownForTitles = listviewitem.findViewById(R.id.numvotes);


        ExtraModel extraModel = extraModelList.get(position);
        String ncostStr ="<b>Cost:  &nbsp;&nbsp;&nbsp;&nbsp;</b>"+extraModel.getNcost();
        String primaryNameStr ="<b> Name:  &nbsp;&nbsp;&nbsp;&nbsp;</b>"+extraModel.getName();
        String birthYearStr ="<b>Birth Year:  &nbsp;&nbsp;&nbsp;&nbsp;</b>"+extraModel.getBirthYear();
        String primaryProfessionStr ="<b>Primary Profession:  &nbsp;&nbsp;&nbsp;&nbsp;</b>"+extraModel.getPrimaryProfession();
        String knownForTitlesStr ="<b>Known for Titles  &nbsp;&nbsp;&nbsp;&nbsp;</b>"+extraModel.getKnownforTitles();

        ncost.setText(Html.fromHtml(ncostStr));
        primaryName.setText(Html.fromHtml(primaryNameStr));
        birthYear.setText(Html.fromHtml(birthYearStr));
        primaryProfession.setText(Html.fromHtml(primaryProfessionStr));
        knownForTitles.setText(Html.fromHtml(knownForTitlesStr));
        return listviewitem;
    }
}

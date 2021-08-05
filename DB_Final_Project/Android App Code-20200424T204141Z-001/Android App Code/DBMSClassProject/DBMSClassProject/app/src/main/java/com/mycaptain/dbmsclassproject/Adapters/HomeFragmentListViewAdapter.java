package com.mycaptain.dbmsclassproject.Adapters;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.mycaptain.dbmsclassproject.Model.HomeModel;
import com.mycaptain.dbmsclassproject.R;
import android.text.Html;
import java.util.List;

import static android.view.View.GONE;

public class HomeFragmentListViewAdapter extends ArrayAdapter<HomeModel> {
    private List<HomeModel> homeModelList;
    private Context context;

    public HomeFragmentListViewAdapter(List<HomeModel> homeModelList, Context context) {
        super(context, R.layout.custom_layout_listview,homeModelList);
        this.homeModelList = homeModelList;
        this.context = context;
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
        LayoutInflater inflater =LayoutInflater.from(context);
        View listviewitem = inflater.inflate(R.layout.custom_layout_listview,null,true);
        TextView title = listviewitem.findViewById(R.id.title);
        TextView region = listviewitem.findViewById(R.id.region);
        //TextView language = listviewitem.findViewById(R.id.language);
        //TextView language_txt = listviewitem.findViewById(R.id.language_txt);
        TextView startyear = listviewitem.findViewById(R.id.startyear);
        TextView averageRating = listviewitem.findViewById(R.id.rating);
        TextView numvotes = listviewitem.findViewById(R.id.numvotes);

       HomeModel homeModel = homeModelList.get(position);
       String strTitle ="<b>Title: &nbsp;&nbsp;&nbsp;&nbsp;</b>"+homeModel.getTitle();
       String strRegion="<b>Region: &nbsp;&nbsp;&nbsp;&nbsp;</b>"+homeModel.getRegion();
       String strStartYear="<b>Start Year: &nbsp;&nbsp;&nbsp;&nbsp;</b>"+homeModel.getStartYear();
       String strAverageRating="<b> Rating: &nbsp;&nbsp;&nbsp;&nbsp;</b>"+homeModel.getAverageRating();
       String strnumvotes="<b>Number of Votes: &nbsp;&nbsp;&nbsp;&nbsp;</b>"+homeModel.getNumvotes();

       title.setText(Html.fromHtml(strTitle));
        region.setText(Html.fromHtml(strRegion));
        startyear.setText(Html.fromHtml(strStartYear));
        averageRating.setText(Html.fromHtml(strAverageRating));
        numvotes.setText(Html.fromHtml(strnumvotes));

        return listviewitem;
    }
}

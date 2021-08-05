package com.mycaptain.dbmsclassproject.Fragment;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.mycaptain.dbmsclassproject.Adapters.SingleListViewAdapter;
import com.mycaptain.dbmsclassproject.Adapters.TvShowsListAdapter;
import com.mycaptain.dbmsclassproject.Model.SingleModel;
import com.mycaptain.dbmsclassproject.R;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Objects;

public class TvShowsFragment extends Fragment {


    private  static final String url= "http://demomvcapp-env.eba-sz3rwy35.us-east-1.elasticbeanstalk.com/Database%20Class%20Project/view/results3.json";
    private ListView listView;
    private ArrayList<SingleModel> singleModelArrayList;
    private ProgressBar progressBar;
    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_tv_shows, container, false);
        listView =view.findViewById(R.id.listview);
        singleModelArrayList = new ArrayList<>();
        progressBar = view.findViewById(R.id.progressbar);
        loadList();
        return view;
    }


    private void loadList() {
        progressBar.setVisibility(View.VISIBLE);
        StringRequest stringRequest = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                progressBar.setVisibility(View.GONE);
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    JSONArray jsonArray = jsonObject.getJSONArray("posts");
                    for(int i=0;i<jsonArray.length();i++){
                        JSONObject jsonObject1 = jsonArray.getJSONObject(i);
                        SingleModel singleModel=new SingleModel(jsonObject1.getString("primaryTitle"));
                        singleModelArrayList.add(singleModel);
                        TvShowsListAdapter adapter = new TvShowsListAdapter(singleModelArrayList,getContext());
                        listView.setAdapter(adapter);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getContext(),"There is an error",Toast.LENGTH_LONG).show();
            }
        });
        RequestQueue requestQueue = Volley.newRequestQueue(Objects.requireNonNull(getContext()));
        requestQueue.add(stringRequest);

    }
}

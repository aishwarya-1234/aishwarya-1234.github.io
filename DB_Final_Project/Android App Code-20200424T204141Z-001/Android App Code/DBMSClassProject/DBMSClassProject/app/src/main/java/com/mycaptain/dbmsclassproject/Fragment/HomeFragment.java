package com.mycaptain.dbmsclassproject.Fragment;

import android.os.Bundle;
import android.util.Log;
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
import com.mycaptain.dbmsclassproject.Adapters.HomeFragmentListViewAdapter;
import com.mycaptain.dbmsclassproject.Model.HomeModel;
import com.mycaptain.dbmsclassproject.R;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Objects;

public class HomeFragment extends Fragment {


    private  static final String url= "http://demomvcapp-env.eba-sz3rwy35.us-east-1.elasticbeanstalk.com/Database%20Class%20Project/view/results1.json";
    private ListView listView;
    private ArrayList<HomeModel> homeModelArrayList;
    private ProgressBar progressBar;
    public View onCreateView(@NonNull LayoutInflater inflater,
                             ViewGroup container, Bundle savedInstanceState) {
        View root = inflater.inflate(R.layout.fragment_home, container, false);
        listView =root.findViewById(R.id.listview);
        homeModelArrayList = new ArrayList<>();
         progressBar = root.findViewById(R.id.progressbar);
        loadhomeList();
        return root;
    }

    private void loadhomeList() {
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
                        HomeModel homeModel =new HomeModel(
                                jsonObject1.getString("title"),
                                jsonObject1.getString("region"),
                                jsonObject1.getString("language"),
                                jsonObject1.getString("startYear"),
                                jsonObject1.getString("averageRating"),
                                jsonObject1.getString("numVotes"));
                        homeModelArrayList.add(homeModel);
                        HomeFragmentListViewAdapter adapter = new HomeFragmentListViewAdapter(homeModelArrayList,getContext());
                        listView.setAdapter(adapter);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getContext(),""+error.toString(),Toast.LENGTH_LONG).show();
                Log.e("TAG",""+error.toString());
            }
        });
        RequestQueue requestQueue = Volley.newRequestQueue(Objects.requireNonNull(getContext()));
        requestQueue.add(stringRequest);
    }
}

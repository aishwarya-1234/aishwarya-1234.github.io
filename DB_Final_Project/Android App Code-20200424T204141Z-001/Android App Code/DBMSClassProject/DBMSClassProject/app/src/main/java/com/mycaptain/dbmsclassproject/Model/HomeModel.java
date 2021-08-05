package com.mycaptain.dbmsclassproject.Model;

public class HomeModel {

    private String title, region,language, startYear,averageRating,numvotes;

    public HomeModel(String title, String region, String language, String startYear, String averageRating, String numvotes) {
        this.title = title;
        this.region = region;
        this.language = language;
        this.startYear = startYear;
        this.averageRating = averageRating;
        this.numvotes = numvotes;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getRegion() {
        return region;
    }

    public void setRegion(String region) {
        this.region = region;
    }

    public String getLanguage() {
        return language;
    }

    public void setLanguage(String language) {
        this.language = language;
    }

    public String getStartYear() {
        return startYear;
    }

    public void setStartyear(String startYear) {
        this.startYear = startYear;
    }

    public String getAverageRating() {
        return averageRating;
    }

    public void setAverageRating(String averageRating) {
        this.averageRating = averageRating;
    }

    public String getNumvotes() {
        return numvotes;
    }

    public void setNumvotes(String numvotes) {
        this.numvotes = numvotes;
    }
}

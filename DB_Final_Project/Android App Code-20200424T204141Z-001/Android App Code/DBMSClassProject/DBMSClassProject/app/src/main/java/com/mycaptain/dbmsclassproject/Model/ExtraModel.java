package com.mycaptain.dbmsclassproject.Model;

public class ExtraModel {

    String ncost;
    String name;
    String birthYear;
    String deathYear;
    String primaryProfession;
    String knownforTitles;

    public ExtraModel(String ncost, String name, String birthYear, String primaryProfession, String knownforTitles) {
        this.ncost = ncost;
        this.name = name;
        this.birthYear = birthYear;
        this.primaryProfession = primaryProfession;
        this.knownforTitles = knownforTitles;
    }

    /*public ExtraModel(String ncost, String name, String birthYear, String deathYear, String primaryProfession, String knownforTitles) {
        this.ncost = ncost;
        this.name = name;
        this.birthYear = birthYear;
        this.deathYear = deathYear;
        this.primaryProfession = primaryProfession;
        this.knownforTitles = knownforTitles;
    }*/

    public String getNcost() {
        return ncost;
    }

    public void setNcost(String ncost) {
        this.ncost = ncost;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getBirthYear() {
        return birthYear;
    }

    public void setBirthYear(String birthYear) {
        this.birthYear = birthYear;
    }
/*

    public String getDeathYear() {
        return deathYear;
    }

    public void setDeathYear(String deathYear) {
        this.deathYear = deathYear;
    }
*/

    public String getPrimaryProfession() {
        return primaryProfession;
    }

    public void setPrimaryProfession(String primaryProfession) {
        this.primaryProfession = primaryProfession;
    }

    public String getKnownforTitles() {
        return knownforTitles;
    }

    public void setKnownforTitles(String knownforTitles) {
        this.knownforTitles = knownforTitles;
    }
}

# PtuneVlog

A simple video library containing youtube videos collection

## Installation step

### 1. Clone git repo using

```
git clone https://github.com/Thedijje/ptunevlog.git
```

### 2. Run composer

```
composer update
```

### 3. Give permission to writable directory

```
chmod -R 777 writable/*
```

### 4. Configure URL/Databse

Open `/index.php` file

* Update your current URL as `base_url`
* Enter/select `db_group` and configure same in `applications/app_1_0/config/database.php`

### 5. Import sample data
* Import sample data using sql provided in `applications/sql` directory


> All apis are in /api/ directory so if requested api is /login, use /api/login to access it

### Used resources
1. Codeigniter framework
2. CI boilerplate for api `https://github.com/seekgeeks/codeigniter_app` 
3. Rest API library for codeigniter `chriskacerguis/codeigniter-restserver`
4. Custome modification in `app_1_0/core/My_controller.php`
5. CI_helper library `https://github.com/thedijje/ci_helper`



## Incomplete points
* Handling all queries for video listing
* Video detail response having missing topics


### References
[How index.php is configures for URL and database](https://medium.com/@thedijje/use-codeigniter-to-manage-multiple-app-and-environment-21f6c26d2fdf)

[CI_helper documentation](https://github.com/Thedijje/CI_helper/blob/master/README.md)

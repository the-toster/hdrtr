# Docker for development

Use `php` service from `compose.yaml` as remote PHP interpreter for PhpStorm.  

Set up your local `UID`/`GID` using `.env` file. See `.env.example`.  
Default is `1000:1000`.  

Access to the shell:

```shell
docker compose run php bash
```
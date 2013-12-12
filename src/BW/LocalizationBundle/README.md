BoW | BWLocalizationBundle
==========================
Компонент для помощи в реализации мультиязычности системы и контента.
Для подключения страниц управления мультиязычностью в свою панель
управления сайтом смотрите файл ```routing.yml``` в этом бандле.

1. Установка
------------

### Установка через composer

На данный момент установка через ```composer``` не поддерживается.
Ожидается поддержка в последующих версиях.


### Автоматическое подключение и ручная установка

#### 1) Сгенерировать новый бандл генератором кода
Выполнить команду из консоли: 

        ```$ php app/console generate:bundle --namespace="BW\LocalizationBundle"```

#### 2) Выполнить замену файлов
Заменить файлы только что сгенерированного бандла на файлы 
с нашим исходным кодом, скаченные с GitHub.


### Ручное подключение и установка

#### 1) Скопировать файлы
Скопировать файлы с нашим исходным кодом, скаченные с GitHub, себе
в директорию ```src/BW/LocalizationBundle``` своего приложения

#### 2) Подключить бандл в ```AppKernel.php```

            public function registerBundles()
            {
                $bundles = array(
                    // other bundles...
                    new BW\LocalizationBundle\BWLocalizationBundle(),
                );

#### 3) Импортировать файл ```routing.yml```

        # app/config/routing.yml
        bw_localization:
            resource: "@BWLocalizationBundle/Resources/config/routing.yml"
            prefix:   /


2. Настройка
------------

### 1) Активировать в файле ```config.yml```

        # app/config/config.yml
        framework:
            # ...
            translator: { fallback: %locale% }
        default_locale: "%locale%"

### 2) Добавить в файл ```parameters.yml```

        # app/config/parameters.yml
        parameters:
            # other params...
            locale: ru

### 3) Изменить свои файлы ```routing.yml```
Добавить в нужные роуты поддержку языка:

        pattern:    /{_locale}/blog

### 4) Обновить схему БД
Выполнить команду из консоли:

        ```$ php app/console doctrine:schema:update --force```

Добавить в созданную таблицу ```langs``` нужные языки.
# bitrix.quickstart
---
Набор скриптов для быстрого старта сайта на Bitrix.

Проект в начальной стадии разработки.

Цель проекта: возможность быстро накатить типовую файловую структуру и ряд сниппетов на свежеустановленный дистрибутив bitrix.

Проект предполагает широкое использование папки /local, в которой расположены все файлы, необходимые разработчику. Папка /bitrix в идеале должна быть полностью занесена в .gitignore, поскольку содержит ядро системы.


---

title: Структура  
description: Документация к Bitrix Quick Start, Структура и назначение файлов
template: index
---


# Структура и назначение файлов Bitrix Quick Start

---
## [Типовая структура проекта на Quick Start:](#tipical)
---

```md
auth/
bitrix/
├── php_interface/
│   ├── this_site_support.php
│   └── include/
│         └── site_closed.php
favicons/
includes/
    └── pages/
    │   ├── 404.php
    │   ├── main.php
    │   └── ui.php
    ├── favicons.php
    ├── footer.php
    ├── header.php
    └── html.php
local/ 
├── codenails/
│   ├── css/
│   ├── images/
│   ├── js/
│   ├── less/
│   └── tools/
├── components/
├── gadgets/
├── logs/
├── modules/
├── php_interface/
│   ├── cn_log.php
│   └── init.php
└── templates/
    ├── .default/
    │   ├── components/
    │   └── page_templates/
    │       └── standard.php
    └── rename_me/
personal/
search/
.htaccess_example
404.php
favicon.ico
index.php
robots.txt
```

---
## [auth/](#auth)
---
Папка, для тех, кто забывает положить форму для восстановления пароля. Ну и авторизация по умолчанию.

---
## [bitrix/php_interface/](#php_interface)
---
- **this_site_support.php** — Информация о партнёре и техподдержке (нужна по требованиям монитора качества). Отображается внизу формы авторизации в админке. Этот файл не подхватывается из local, возможно со временем это исправят.
_**Информация о поддержке и партнёре**_
- **include/site_closed.php** — [Красивая заглушка](http://jsfiddle.net/eoq287rd/embedded/result/) для отключенной публички. К сожалению пока этот файл не подхватывается из папки local.

---
## [favicons/](#favicons)
---

Папка с различными иконками под все устройства о основные ОС, нужны для красивого отображения сайта при добавлении в закладки, на рабочие столы и т.д.

Для генерации иконок очень хорошо подходит сервис [realfavicongenerator](http://realfavicongenerator.net/).

<div class="tip">
    :facepunch: Не забываем пнуть ленивого дизайнера, чтоб отрисовал нормальные иконки.
</div>

---
## [includes/](#includes)
---
В этой папаке располагаются включаемые области и прочие php-файлы, которые контент-менеджер или клиент может отредактировать через веб-интерфейс битрикса, из публичной части.

---
## [includes/pages/](#pages)
---
В этой папке располагаются свёрстанные страницы проекта.
Удобство такого расположения очевидно: файлы можно подключать как включаемые области непосредственно в проект, или просто подглядывать в них в процессе интеграции вёрстки в битрикс.
- **404.php** — 404 страничка (не путать с [файлом в корне](#404_page), этот файл — просто вёрстка контента)
- **main.php** — Главная страница.
- **ui.php** — UIKit, тут собраны все основные элементы, для удобства проверки и стилизации элементов.

---
## [local/](#local)
---
Основная папка проекта. 

#### [local/codenails/](#codenails)
В этой папке содержатся файлы, относящиеся к фронтенду (стили, скрипты, картинки шаблона), а так же включаемые области и php-файлы, отвечающие на ajax-запросы.

#### [local/codenails/css/](#codenails_css)

- template_styles.css — Скомпилированный CSS-файл, который подключается в шаблон. 
- Так же в эту папку складываем все CSS-файлы, которые не требуется включать с LESS по различным соображениям (*к примеру не требующий правок файл какого-нибудь плагина для jQuery*). Файлы подключаются в автоматическом через [cnAsset](https://github.com/pafnuty/cnAsset).

#### [local/codenails/images/](#codenails_images)

- В эту папку кладём картинки шаблона. 
- Если нужны временные картинки, не нужно копировать их из макета, [используйте сервис](http://imgholder.ru/) (и плагин для SublimeText) **не захламляйте папку.**
- **ie_logo.png** - не удаляем картинку, она отображается в админке (см раздел про минитор качества)

#### [local/codenails/js/](#codenails_js)

- Сюда складываем js-файлы и jquery-плагины, необходимые для работы шаблона, которые будут подгружаться через автозагрузчик cnAsset.
- **main.js** — основной js-файл шаблона.

#### [local/codenails/less/](#codenails_less)

Сюда складываем LESS-файлы проекта. Об организации less файлов написано в разделе [LESS](/documentation/less).


---
## [local/components/](#components)
---

Папка для размещения собственных компонентов.

---
## [local/gadgets/](#gadgets)
---

Папка для размещения гаджетов рабочего стола (*возможно когда-нибудь мы будем туда класть загортовки гаджетов*)

---
## [local/logs/](#logs)
---
Папка для хранения логов (туда автоматом записывается лог, создаваймый классом CNLog).

---
## [local/modules/](#modules)
---
Папка для размещения собственных модулей.

---
## [local/php_interface/](#php_interface)
---
- **cn_log.php** — Класс для ведения наглядного лога, нужен для удобной отладки того, что тяжело отладить (*пример вызова в init.php*)
- **init.php** — init.php сайта, в который подключается дефолтный init.php. Так же там присутствуют некоторые, полезные во всех проектах, функции.


---
## [local/templates/](#templates)
---
- **.default** — папка с шаблонами компонентов.
    + **page_templates/standard.php** — шаблон для новых страниц (вместо глупой надписи "text here");
- **rename_me** — папка с шаблоном сайта (*не забываем переименовать*).
    + **header.php** — шапка сайта.
    + **footer.php** — подвал сайта.
    + **description.php** — описание шаблона.

---
## [search/](#search)
---
Папка, отвечающая за поиск по сайту и формирование карты сайта (*не sitemap.xml, а непонятная сущность, формирующаяся из меню сайта*).

### [.htaccess_example](#htaccess_example)
Не забываем удалить этот файл, предварительно прочитав и сделав, как написано. Тут лежит пример правильной склейки зеркал для apache.

### [404.php](#404_page)
404-я страничка.

---
---
## Autoload
---

Автозагрузка классов.

Больше не придется писать `CModule::IncludeModule('iblock');` в каждом компоненте или скрипте.

Инициализация автолоадера происходит при создании нового экземпляра класса \BitrixQuickStart\Autoloader() в файле init.php.

Большинство стандартных модулей, указанных в документации, будут подключаться автоматически. Это реализовано за счет «таблицы» соответствий между названиями классов и модулями. Например:

    '/^CIBlock/' => 'iblock'
    
Данное выражение указывает автолоадеру, что все классы, начинающиеся на 'CIBlock' относятся к модулю информационных блоков. «Таблица» соответствий захардкожена в классе \BitrixQuickStart\Autoloader(), и может быть расширена методом `addClassMapItem`. Пример:

    $autoloader = new \BitrixQuickStart\Autoloader();
    $autoloader->addClassMapItem('/^CFooBar/', 'foobar');
    
Теперь все методы классов, начинающихся на 'CFooBar' будут работать без отдельного подключения модуля 'foobar'.

Автолоадер подключает также все пользовательские классы, расположенные в папке /local/classes. Каждый класс должен располагаться в отдельном файле, имя которого должно совпадать с названием класса. Например, класс UserHandlers должен быть расположен по адресу `/local/classes/UserHandlers.php`.

Если нужно задать дополнительную директорию, в которой хранятся пользовательские классы, можно воспользоваться методом `addAutoloadPath`. Пример:

    $autoloader = new \BitrixQuickStart\Autoloader();
    $autoloader->addAutoloadPath('/includes/my/classes/');
    
Путь указывается от корня сайта.

## Базовый шаблон сайта

Типовая файловая структура bitrix-шаблона расположена по адресу `/local/templates/main`.

Общие рекомендации по шаблонам bitrix:

1. Все представления (`views`), включая шаблоны компонентов, лучше помещать в папку основного шаблона сайта: `/local/templates/main/components`. Впоследствии это может упросить жизнь при проведении редизайна сайта.

2. Лучше избегать большого количества шаблонов сайта, поскольку сторонний разработчик для внесения минимальной правки в шаблон потратит больше времени на поиск нужного шаблона, нежели на внесение изменений.

3. Избегайте использования автоматически подключаемых файлов style.css в шаблонах компонентов, т.к. это затрудняет внесение изменений. Используйте стандартный автоподключаемый файл styles.css в корне шаблона сайта в качестве единого файла стилей.

4. Клиентскую логику лучше выносить в файлы script.js, которые расположены в шаблонах компонентов и подключаются автоматически. Использовать единый файл с обработчиками событий DOM не возбраняется, но необходимо обязательно комментировать, к какой части страницы относится тот или иной JavaScript-код и где он применяется.

## Robots.txt

Файл полностью закрывает сайт от индексации поисковиками. Это делается для того, чтобы тестовые площадки, размещенные в сети, не оттягивали на себя трафик, отображаясь в результатах поиска наравне с основным сайтом.

В дальнейшем рекомендуется не отслеживать изменения robots.txt, поскольку последние версии bitrix позволяют контент-редакторам и SEO-специалистам править robots.txt через админку.

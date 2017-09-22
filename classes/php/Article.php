<?php
//Класс вывода всех статей на главной странице с описанием книги
class Article
{
    //Файл html-шаблона статьи
    protected $template;

    function __construct($template)
    {
        $this->template = file_get_contents($template);// прочитать файл в переменную
    }

    //Читает и возвращает преобразованный файл шаблона статьи.
    //Функции передается предварительно полученный программой  результирующий набор mysqli_result,
    // полученный из запроса в базу данных
    public function readTemplate($mysqli_object)
    {
        //Переменная, которая будет возвращена методом
        $content = "";
        //Массив меток-заполнителей в html-шаблоне, которые будут заменены на результаты,
        //Полученные из базы данных
        $needle = array("[article_id]", "[cover_url]", "[title]", "[author]", "[year]", "[text]");
        //Объект
        $db_object = $mysqli_object->fetch_object();
        //Если объект не пустой
        if ($db_object != "") {
            //Переместить указатель результата в начало
            $mysqli_object->data_seek(0);
            //Запускать цикл, пока присутсвует очередная строка результата
            while ($db_object = $mysqli_object->fetch_object()) {
                //Присвоить переменной ссылку на файл шаблона, т.к. непосредственное обращение к $this->template в цикле
                //в каждом проходе цикла осуществляет новое чтение файла шаблона, что сказывается на быстродействии
                $cont = $this->template;
                //Разбить полученный из базы текст статьи пр разделителю [end] и поместить в массив; нулевой элемент массива
                //будет соответсвовать первой строке, т.е. в данном случае первой строке до разделителя [end]
                $cutted_text = explode("[end]", $db_object->text);
                //Обновляющийся при каждом проходе цикла массив значений из базы данных, значения которого будут заменять
                //метки-заполнители (массив $needle) полученного файла html-шаблона
                $replace = array($db_object->id, $db_object->id, $db_object->title, $db_object->author, $db_object->year, $cutted_text[0]);
                //Заменить массив меток-заполнителей на массив значений базы данных в файле шаблона
                $cont = str_replace($needle, $replace, $cont);
                //Результаты распарсенного с замененными значениями шаблона на каждом проходе цикла конкатенируются в этой переменной
                $content .= "$cont";
            }
        } else {
            //если $db_object пуст
            $content = "<h3 class='text-center'> В данной категории пока еще нет книг</h3>";
        }
        //вернуть строку (можно сказать, что возвращается html-разметка) с замененными значениями
        return $content;
    }

}
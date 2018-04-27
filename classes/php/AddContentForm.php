<?php

/**
 * Class AddContentForm
 * Класс вывода html-формы добавления контента в базу данных в админ-панели сайта
 */
class AddContentForm extends Article
{
    /**
     * AddContentForm constructor.
     * @param string $template Путь к файлу шаблона
     */
    function __construct($template)
    {
        /**
         * Вызов конструктора суперкласса
         */
        parent::__construct($template);
    }

    /**
     * Читает и возвращает преобразованный файл шаблона статьи.
     * Функции передается предварительно полученный из базы данных результирущий набор mysqli_result
     * @param string $mysqli_object
     * @return mixed|string
     */
    public function readTemplate($mysqli_object = '')
    {
        /**Переменная, которая будет возвращена методом.
         *
         * Будет содержать html-разметку с извлеченными из базы данных значениями, которые будут вставлены на место
         * меток-заполнителей
         * @var string
         */
        $content = '';
        /**
         * Массив меток-заполнителей в html-шаблоне, которые будут заменены на результаты, полученные из базы данных
         * @var array
         */
        $needle = array('[item]', '[category_id]');
        /**
         * Объект - полученный из базы результирующий набор
         */
        $db_object = $mysqli_object->fetch_object();
        //Вырезать из шаблона часть между метками [while] (html элемент options) и результат поместить в массив $items
        //$items[1] будет содержать вырезанную часть, которая и будет обрабатыватьбя в цикле
        preg_match("/\[while\](.*?)\[while\]/s", $this->template, $items);
        /**
         * если объект не пустой
         */
        if ($db_object != "") {
            /**
             * переместить указатель результата в начало
             */
            $mysqli_object->data_seek(0);
            /**
             * запускать цикл до тех пор, пока присутствует очередная строка результата
             */
            while ($db_object = $mysqli_object->fetch_object()) {
                //Присвоить переменной ссылку на вырезанную часть шаблона, т.к. непосредственное обращение к items[1] в цикле
                //в каждом проходе цикла осуществляет новое чтение файла шаблона, что сказывается на быстродействии
                $temp_template = $items[1];
                //массив с полученными из базы данных значениями, которые и будут заменять метки-заполнители html шаблона
                $replace = array($db_object->name, $db_object->category_id);
                //заменить массив меток-заполнителей из вырезанной части html шаблона на массив полученных из базы данных значений
                $temp_template = str_replace($needle, $replace, $temp_template);
                //Результаты распарсенного с замененными значениями шаблона на каждом проходе цикла конкатенируются в этой переменной
                $content .= "$temp_template";
            }
            //Вместо [while]...[while] из файла шаблона вклеить сгенерированную разметку из переменной $menu
            $content = preg_replace("/\[while\](.*?)\[while\]/s", $content, $this->template);;
        } else $content = '';//если db_object пуст
        //вернуть строку (можно сказать, что возвращается html-разметка) с замененными значениями
        return $content;
    }
}
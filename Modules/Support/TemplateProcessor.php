<?php

namespace Modules\Support;

use Illuminate\Support\Str;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\TemplateProcessor as DefaultTemplateProcessor;

class TemplateProcessor extends DefaultTemplateProcessor
{
    /**
     * @param mixed $search
     * @param mixed $replace
     * @param int $limit
     */
    public function setValue($search, $replace, $limit = self::MAXIMUM_REPLACEMENTS_DEFAULT)
    {
        if (is_array($search)) {
            foreach ($search as &$item) {
                $item = static::ensureMacroCompleted($item);
            }
            unset($item);
        } else {
            $search = static::ensureMacroCompleted($search);
        }

        if (is_array($replace)) {
            foreach ($replace as &$item) {
                $item = static::ensureUtf8Encoded($item);
            }
            unset($item);
        } else {
            $replace = static::ensureUtf8Encoded($replace);
        }

        if (Settings::isOutputEscapingEnabled()) {
            $find = ['</w:t><w:t>', '<w:br />', '</w:t><w:rPr><w:rFonts w:ascii="Tinos" w:hAnsi="Tinos" w:cs="Tinos"/><w:color w:val="FF0000"/></w:rPr><w:t>'];
            $repl = ['ltwtgtltwtgt', 'ltwbrgtltwbrgt', 'ltwtgtltwrPrgtltwrFontswasciiTinoswhAnsiTinoswcsTinos'];
            $replace = str_replace($find, $repl, $replace);

            $xmlEscaper = new Xml();
            $replace = $xmlEscaper->escape($replace);
            $replace = str_replace($repl, $find, $replace);
        }

        $this->tempDocumentHeaders = $this->setValueForPart($search, $replace, $this->tempDocumentHeaders, $limit);
        $this->tempDocumentMainPart = $this->setValueForPart($search, $replace, $this->tempDocumentMainPart, $limit);
        $this->tempDocumentFooters = $this->setValueForPart($search, $replace, $this->tempDocumentFooters, $limit);
    }
}

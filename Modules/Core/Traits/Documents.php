<?php

namespace Modules\Core\Traits;

trait Documents
{
    protected $defaultFont = ['name' => 'Roboto Condensed', 'size' => 10, 'color' => '323E4F'];
    protected $titleStyle = ['name' => 'Roboto Condensed', 'size' => 10, 'color' => '323E4F'];
    protected $defaultTableStyle = [ 'borderSize' => 5, 'borderColor' => '000000'];
    protected $borderNone = [ 'borderSize' => 5, 'borderColor' => 'FFFFFF'];
    protected $cellBgColor = ['bgColor' => 'FFF5E5'];
    protected $noSpace = ['align' => 'center', 'spaceAfter' => 0];
    
    protected function getMissingTag($text)
    {
        return '</w:t><w:rPr><w:rFonts w:ascii="Roboto Condensed" w:hAnsi="Roboto Condensed" w:cs="Roboto Condensed"/><w:color w:val="FF0000"/></w:rPr><w:t>[' . $text . "]</w:t><w:t>";
    }

    protected function getHighlightTag($text)
    {
        return "</w:t><w:t>{$text}</w:t><w:t>";
    }

    protected function getTag($text)
    {
        return "</w:t><w:t>{$text}</w:t><w:t>";
    }

    protected function checkbox()
    {
        return '</w:t></w:r><w:r w:rsidR="00E54FE3"><w:rPr><w:rFonts w:ascii="Segoe UI Emoji" w:hAnsi="Segoe UI Emoji" w:cs="Segoe UI Emoji"/><w:color w:val="323E4F" w:themeColor="text2" w:themeShade="BF"/><w:kern w:val="0"/><w:sz w:val="20"/><w:szCs w:val="20"/><w14:ligatures w14:val="none"/></w:rPr><w:t>✅</w:t></w:r><w:r><w:rPr><w:rFonts w:ascii="Segoe UI Emoji" w:hAnsi="Segoe UI Emoji" w:cs="Segoe UI Emoji"/><w:color w:val="323E4F" w:themeColor="text2" w:themeShade="BF"/><w:kern w:val="0"/><w:sz w:val="20"/><w:szCs w:val="20"/><w14:ligatures w14:val="none"/></w:rPr><w:t>';
    }

    protected function prohibited()
    {
        return '</w:t></w:r><w:r w:rsidR="00E54FE3"><w:rPr><w:rFonts w:ascii="Segoe UI Emoji" w:hAnsi="Segoe UI Emoji" w:cs="Segoe UI Emoji"/><w:color w:val="323E4F" w:themeColor="text2" w:themeShade="BF"/><w:kern w:val="0"/><w:sz w:val="20"/><w:szCs w:val="20"/><w14:ligatures w14:val="none"/></w:rPr><w:t>🚫</w:t></w:r><w:r><w:rPr><w:rFonts w:ascii="Segoe UI Emoji" w:hAnsi="Segoe UI Emoji" w:cs="Segoe UI Emoji"/><w:color w:val="323E4F" w:themeColor="text2" w:themeShade="BF"/><w:kern w:val="0"/><w:sz w:val="20"/><w:szCs w:val="20"/><w14:ligatures w14:val="none"/></w:rPr><w:t>';
    }

    protected function formatString($text)
    {
        if(is_null($text) || empty($text)) { return null; }

        return str_replace("\n","<w:br />", $text);
    }
}

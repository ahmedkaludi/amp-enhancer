<?php 

function amp_enhancer_ul_addon_gutenberg_functionalities($content){
  // Table of Content Functionality
 if(strpos($content, 'wp-block-uagb-table-of-contents') > -1 || strpos($content, 'uagb-toc__title') > -1 ){

      $dom = new domdocument();
      // To Suppress Warnings
          libxml_use_internal_errors(true);
          $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
         //   $dom->loadHTML($menu);
          libxml_use_internal_errors(false);

      $xpath = new domxpath($dom);

      $headings = $xpath->query("//h1 | //h2 | //h3 | //h4 | //h5 | //h6");
        foreach ($headings as $htag) {
            $inner_html = strtolower($htag->textContent);
            $inner_html = str_replace(array(' ','?'), array('-',''), $inner_html);
            $id = $htag->getAttribute('id');
            if(isset($id) && $id == false){
              $htag->setAttribute('id', $inner_html);
            }
          }

    $content =  $dom->saveHTML();

  }
  // FAQ Accordion Functionality
  if(preg_match('/<div\s+class="uagb-faq-questions-button(.*?)">(.*?)<\/div>/s', $content)){

       $content = preg_replace('/<div\s+class="uagb-faq-questions-button(.*?)">(.*?)<\/div>/s', '<h2 class="uagb-faq-questions-button$1 uagb-faq-item">$2</h2>', $content);
       $content = preg_replace('/<div\s+class="uagb-faq-content(.*?)">(.*?)<\/div>/s', '<span class="uagb-faq-content$1">$2</span>', $content);
       $content = preg_replace('/<div\s+class="uagb-faq-item(.*?)">(.*?)<\/div>/s', '$2', $content);
       $content = preg_replace('/<div\s+class="uagb-faq-child__wrapper(.*?)">(.*?)<\/div>/s', '$2', $content);

       $content = preg_replace('/<div\s+class="wp-block-uagb-faq-child(.*?)">(.*?)<\/div>/s', '<section class="wp-block-uagb-faq-child$1">$2</section>', $content);

       $content = preg_replace('/<div\s+class="uagb-faq__wrap(.*?)">(.*?)<\/div>/s', '<amp-accordion class="uagb-faq__wrap$1" expand-single-section>$2</amp-accordion>', $content);
   }

  return $content;
}
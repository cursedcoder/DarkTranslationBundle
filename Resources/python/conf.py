# -*- coding: utf-8 -*-
import sys, os

sys.path.append(os.path.abspath('.'))

extensions = ['sensio.sphinx.refinclude', 'sensio.sphinx.configurationblock', 'sensio.sphinx.phpcode', 'sphinxcontrib.phpdomain']

templates_path = ['_templates']
source_suffix = '.rst'
master_doc = 'index'
project = u'symfony'
copyright = u'2012, ads'
version = 'symfony'
release = 'symfony'
exclude_patterns = []
html_theme_path = ['.']
html_theme = 'symfony'

man_pages = [
    ('index', 'symfony', u'symfony Documentation',
     [u'ads'], 1)
]

texinfo_documents = [
  ('index', 'symfony', u'symfony Documentation',
   u'ads', 'symfony', 'One line description of project.',
   'Miscellaneous'),
]

pygments_style = 'native'
primary_domain = 'php'
highlight_language = 'php'

from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer
lexers['php'] = PhpLexer(startinline=True)
lexers['php-annotations'] = PhpLexer(startinline=True)
primary_domain = "php"

api_url = 'http://api.symfony.com/master/%s'
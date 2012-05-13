# -*- coding: utf-8 -*-
import sys, os

sys.path.append(os.path.abspath('.'))

extensions = ['configurationblock', 'sphinxcontrib.phpdomain']

templates_path = ['_templates']
source_suffix = '.rst'
master_doc = 'index'
project = u'asd'
copyright = u'2012, ads'
version = 'asd'
release = 'asd'
exclude_patterns = []
html_theme_path = ['.']
html_theme = 'symfony'

man_pages = [
    ('index', 'asd', u'asd Documentation',
     [u'ads'], 1)
]

texinfo_documents = [
  ('index', 'asd', u'asd Documentation',
   u'ads', 'asd', 'One line description of project.',
   'Miscellaneous'),
]

pygments_style = 'native'
primary_domain = 'php'
highlight_language = 'php'

from sphinx.highlighting import lexers
from pygments.lexers.web import PhpLexer
lexers['php'] = PhpLexer(startinline=True)
lexers['php-annotations'] = PhpLexer(startinline=True)
primary_domain = "php"    # It seems to help sphinx in some kind (don't know why)
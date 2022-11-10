.. Copyright (C) 2010-2022 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _FileSelect:

FileSelect
==========

Class FileSelect

----

.. include:: /manual/Component/Input/FileSelect/FileSelectAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIFileSelect**

:Syntax:

.. code-block:: twig

    {% UIFileSelect Type {Parameters} %}

:Type:

+--------------------------------------+----------------------+
| :ref:`Standard <FileSelectStandard>` | @param string $sName |
+--------------------------------------+----------------------+

.. _FileSelectStandard:

FileSelect Standard
^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIFileSelect Standard {sName:'value', sId:'value'} %}

:parameters:

+-------+--------+-----------+------+--+
| sName | string | mandatory |      |  |
+-------+--------+-----------+------+--+
| sId   | string | optional  | NULL |  |
+-------+--------+-----------+------+--+

FileSelect common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

+-----------------------------+--------+------------------------------------------------------------+
| AddCSSClass                 | string | CSS class to add to the generated html block               |
+-----------------------------+--------+------------------------------------------------------------+
| AddCSSClasses               | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+--------+------------------------------------------------------------+
| AddCssFileRelPath           | string |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| AddHtml                     | string |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| AddJsFileRelPath            | string |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| AddMultipleCssFilesRelPaths | array  |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| AddMultipleJsFilesRelPaths  | array  |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| ButtonText                  | string |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| CSSClasses                  | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+--------+------------------------------------------------------------+
| DataAttributes              | array  | Array of data attributes in the format ['name' => 'value'] |
+-----------------------------+--------+------------------------------------------------------------+
| FileName                    |        |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| IsHidden                    | bool   |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| ShowFilename                | bool   |                                                            |
+-----------------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Input/FileSelect/FileSelectFooter.rst

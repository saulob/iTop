.. Copyright (C) 2010-2022 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _SelectOption:

SelectOption
============

Class SelectOption

----

.. include:: /manual/Component/Input/Select/SelectOptionAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UISelectOption**

:Syntax:

.. code-block:: twig

    {% UISelectOption Type {Parameters} %}

:Type:

+------------------------------------------------------+------------+
| :ref:`ForSelectOption <SelectOptionForSelectOption>` | No comment |
+------------------------------------------------------+------------+

.. _SelectOptionForSelectOption:

SelectOption ForSelectOption
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UISelectOption ForSelectOption {sValue:'value', sLabel:'value', bSelected:true, sId:'value'} %}

:parameters:

+-----------+--------+-----------+------+--+
| sValue    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sLabel    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| bSelected | bool   | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sId       | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

SelectOption common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

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
| CSSClasses                  | array  | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+--------+------------------------------------------------------------+
| DataAttributes              | array  | Array of data attributes in the format ['name' => 'value'] |
+-----------------------------+--------+------------------------------------------------------------+
| Disabled                    | bool   |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| IsHidden                    | bool   |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| Label                       | string |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| Selected                    | bool   |                                                            |
+-----------------------------+--------+------------------------------------------------------------+
| Value                       | string |                                                            |
+-----------------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Input/Select/SelectOptionFooter.rst

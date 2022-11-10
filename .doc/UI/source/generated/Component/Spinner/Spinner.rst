.. Copyright (C) 2010-2022 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Spinner:

Spinner
=======

Class Spinner

----

.. include:: /manual/Component/Spinner/SpinnerAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UISpinner**

:Syntax:

.. code-block:: twig

    {% UISpinner Type {Parameters} %}

:Type:

+-----------------------------------+------------+
| :ref:`Standard <SpinnerStandard>` | No comment |
+-----------------------------------+------------+

.. _SpinnerStandard:

Spinner Standard
^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UISpinner Standard {sId:'value'} %}

:parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

Spinner common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^

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
| IsHidden                    | bool   |                                                            |
+-----------------------------+--------+------------------------------------------------------------+

----

.. include:: /manual/Component/Spinner/SpinnerFooter.rst

import sphinx_redactor_theme

html_theme = "sphinx_redactor_theme"
html_theme_path = [sphinx_redactor_theme.get_html_theme_path()]

# -- Project information -----------------------------------------------------

project = "events"
copyright = "2021, devguerreiro"
author = "devguerreiro"

# The full version, including alpha/beta/rc tags
release = "v1.0.0"


# -- General configuration ---------------------------------------------------

# Add any Sphinx extension module names here, as strings. They can be
# extensions coming with Sphinx (named 'sphinx.ext.*') or your custom
# ones.
extensions = [
    "sphinx.ext.autodoc",
    "sphinxcontrib.redoc",
]

# config redoc to autogenerate html schema api
redoc = [
    {"name": "Event Documentation", "page": "src/redoc_api", "spec": "api/schema.yml", "embed": True},
]

# Add any paths that contain templates here, relative to this directory.
templates_path = ["_templates"]

# List of patterns, relative to source directory, that match files and
# directories to ignore when looking for source files.
# This pattern also affects html_static_path and html_extra_path.
exclude_patterns = []


# -- Options for HTML output -------------------------------------------------

# The theme to use for HTML and HTML Help pages.  See the documentation for
# a list of builtin themes.
#

# Add any paths that contain custom static files (such as style sheets) here,
# relative to this directory. They are copied after the builtin static files,
# so a file named "default.css" will overwrite the builtin "default.css".
html_static_path = ["_static"]

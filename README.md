AutoLink
========

This plugin to [Mantis Bugtracker](http://www.mantisbt.org/) allows creating
links based on string matches in text.  It is based on the [LinkText]
(http://ascend4.org/Automatic_URL_linking_to_MediaWiki_and_ViewVC_from_Mantis)
plugin, but supports configuration of the links per project, instead of just
global hardcoded links.

Installation
------------

Clone this repository to a subdirectory inside your Mantis plugins directory.

	cd /path/to/mantis/plugins
	git clone https://github.com/make-all/AutoLink.git

The following branches are available:
* mantis-1.2.x: Obsolete. No further changes are planned on this branch.
* mantis-1.3.x: Working, bugfixes for this branch will be accepted.
* master: Working, main development focus is on this branch.

After cloning and checking out the branch corresponding to your mantis version, enable the plugin in the *Manage* menu of Mantis.


Configuration
-------------

NOTE: There are a number of global options, which are inherited from the *MantisFormattingPlugin*. If you have enabled any other formatting plugin, you probably need to disable some of these to avoid double processing.

In addition to the global options, the main purpose of this plugin is to create rules for detecting text patterns and reformatting them. Typically the reformatting will be to create links to external systems such as wiki's, third party bugtrackers, test management systems etc, but the plugin is flexible enough that any kind of formatting or text replacement can be handled, so it could for example be used to insert emoji glyphs for certain keywords if you are so inclined.

Each rule has three fields:

**Project** rules take effect also in sub-projects unless a more specific rule exists.  Global rules can be created using `<All Projects>`.

**Pattern** is a Perl regular expression which selects the text to match.

**Replacement** is a string to replace the pattern with.  Typically it will be HTML, and may contain references to Pattern. `$0` is the full pattern string, `$1`, `$2`, etc can be used to extract sub-expressions from the string.  See some good documentation on [regular expressions](http://php.net/manual/en/book.pcre.php) if that sounds interesting but you have no idea what it means.

Examples
--------

1) To replace `[[WikiLink]]` with a link to Wikipedia.

**Pattern:** `/\[\[(.*)\]\]/`

**Replacement:** `<a href="http://en.wikipedia.com/wiki/$1">$1</a>`


2) To replace BUG-1234 with a link to bug number 1234 in the main mantis
project bug tracker:

**Pattern:** `/\bBUG-([0-9]+\b/)`

**Replacement:** `<a href="http://www.mantisbt.org/bugs/view.php?id=$1">$0</a>`


3) To replace {bug} with an emoji bug,

**Pattern:** `/\{bug\}/`

**Replacement:** `&#128027;`

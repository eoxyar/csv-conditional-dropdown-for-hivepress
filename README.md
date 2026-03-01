WP Plugins
The new version v18 , is installable plugin, fully functional, manageable in admin side.
The first version must unzip first. There are 2 earlier test versions.
Adding 2 cols conditional dropdown in listing submit page/
Contain some "cosmetics" of the first version making more universal.
I continued with this selector to see how many lines
it reads in a reasonable time or if it is
an option if we have an attribute with thousands of options.
I wonder which is more efficient, faster.
I leave it to you to continue testing it, maybe it is good for something.
<img width="873" height="554" alt="image" src="https://github.com/user-attachments/assets/4880aa7b-3084-42a2-9ca0-086b8bab849d" />


In wp settings>location-selector
/wp-admin/options-general.php?page=location-selector-settings

you will find the location selector where you can change
the display areas for the attributes and change the labels,
you can also choose which .csv file you want to use.
The .csv file is located in this plugin folder.

<img width="1258" height="586" alt="image" src="https://github.com/user-attachments/assets/7b8c3784-d734-4b3e-b4db-0afcef5592e0" />

Hope selector js work well for you and do not cuasing page reload
Use  pagerefresh.js if you want to populate cities according county after category is changed in the form filling process and need reselect them.
IMPORTANT ===== do not let spaces beetwen text in your csv file use  "-"  or  "_"  and also special characters  are prohibited like ' .
Try hivepress conditional dropdowns v18, (27.02.2026)
<img width="1294" height="939" alt="Screenshot" src="https://github.com/user-attachments/assets/e243d7ee-b604-47ac-be16-3921836e3db7" />
<img width="1303" height="942" alt="Screenshot2" src="https://github.com/user-attachments/assets/a390a987-4adc-4fb9-8530-10b1f122ec18" />
<img width="1329" height="942" alt="Screenshot3" src="https://github.com/user-attachments/assets/6d672f54-b5b8-4072-aee2-d62f267dbb7e" />

Create a pair: give it a name, set parent slug + label and child slug + label.
Optionally restrict to one or more listing categories. Leave empty to show in all categories.
Go to "Conditional Data" and enter rows: parent value → child value. Or use the CSV import.
The fields appear automatically in Add Listing, Edit Listing, Search and Filter — no code needed.
Can't filter Alfa Romeo; must use Alfa_Romeo. NO SPACES or SPECIAL CHARACTERS in fields.
DON'T FORGET, this is a PLUGIN; if you disable it, data will DISAPPEAR from listings.

# HivePress Conditional Dropdowns

**Version:** 2.0.2  
**Requires WordPress:** 5.8+  
**Requires PHP:** 7.4+  
**Requires:** HivePress (any recent version)

---

## What It Does

Adds conditional (dependent) dropdown field pairs to HivePress listing forms. When a user selects a value in a **parent** dropdown, the **child** dropdown automatically updates to show only the relevant options.

**Example:** User selects **Ford** in the Make field → the Model field instantly shows only Ford models (Fiesta, Focus, Mustang). If they change to **Toyota**, the Model field updates to show Toyota models.

Works across all HivePress form contexts:
- Add Listing form
- Edit Listing form
- Search form
- Filter form (listings page)
- Admin listing edit page (WordPress backend)

---

## How It Works

The plugin registers your custom fields as real HivePress listing attributes so they integrate natively with HivePress search, filtering, and display. The conditional behavior (hiding/showing child options based on parent selection) is handled entirely in JavaScript — no page reloads, no AJAX calls during interaction.

All parent→child data is loaded once on page load as an inline JSON object, making the dropdowns instant and reliable.

---

## Installation

1. Upload the `hpcd` folder to `/wp-content/plugins/`
2. Activate the plugin in **WordPress → Plugins**
3. Go to **HivePress → Conditional Fields** to configure your first pair

---

## Configuration

### Creating a Field Pair

Go to **HivePress → Conditional Fields → Add New Pair**.

| Setting | Description |
|---------|-------------|
| **Pair Name** | Internal label for this pair (e.g. "Make / Model") |
| **Parent Field Name** | Slug for the parent field (e.g. `make`) |
| **Parent Field Label** | Display label shown to users (e.g. "Make") |
| **Child Field Name** | Slug for the child field (e.g. `model`) |
| **Child Field Label** | Display label shown to users (e.g. "Model") |
| **Category Restriction** | Optionally limit this pair to specific listing categories |
| **Enable Search** | Show fields in the search form |
| **Enable Filter** | Show fields in the listings filter |
| **Block Display** | Where to show the value on listing cards (primary / secondary / hide) |
| **Page Display** | Where to show the value on the listing detail page |
| **Parent Icon** | FontAwesome icon class for the parent field (e.g. `fas fa-car`) |
| **Child Icon** | FontAwesome icon class for the child field |
| **Display Format** | How the value is rendered on listing cards/pages. Supports tokens: `%icon%`, `%label%`, `%value%` |

### Managing Data

After creating a pair, go to **HivePress → Conditional Fields → [Pair Name] → Data** to enter the parent→child mappings.

Each row is a **parent value → child value** relationship. For example:

| Parent Value | Child Value |
|-------------|-------------|
| Ford | Fiesta |
| Ford | Focus |
| Ford | Mustang |
| Toyota | Corolla |
| Toyota | Camry |

**Tip:** Use underscores instead of spaces in values (e.g. `Alfa_Romeo` instead of `Alfa Romeo`) to avoid URL encoding issues when filtering.

### CSV Import / Export

For large datasets, use the CSV import feature instead of entering rows manually.

**CSV format:**
```
parent_value,child_value
Ford,Fiesta
Ford,Focus
Toyota,Corolla
Toyota,Camry
```

- Go to **Data** tab for the pair
- Click **Import CSV** and upload your file
- Existing data can be cleared before import or merged

To back up or migrate your data, use **Export CSV** which downloads the current data in the same format.

---

## Category Restriction

If you want a field pair to only appear for specific listing categories (e.g. Make/Model only for vehicle listings):

1. Edit the pair
2. Select one or more categories in the **Category Restriction** field
3. Save

The fields will be hidden in both forms and listings for all other categories. If no categories are selected, the pair is active for all categories.

---

## Display Format

The plugin rewrites attribute display on listing cards and detail pages client-side using a configurable format string.

**Default format:** `%icon% %label%: %value%`

**Tokens:**
- `%icon%` — renders the FontAwesome icon if set
- `%label%` — the field label
- `%value%` — the selected value

**Example custom formats:**
- `%value%` — show only the value, no label
- `%icon% %value%` — icon + value, no label
- `%label%: %value%` — label and value, no icon

---

## Filter Loop Fix

HivePress re-renders the listings block via AJAX when filter dropdowns change. Without special handling, changing a conditional dropdown would cause the page to reload in a loop when results were found.

The plugin solves this by:
1. Using **`stopImmediatePropagation()`** in the capture phase on the parent field's change event — our handler processes the cascade but HivePress's filter listener never sees the event
2. Dispatching a **non-bubbling** change event for cascading child→grandchild pairs so our own handlers still fire
3. Pausing the **MutationObserver** during DOM manipulation to prevent false re-trigger detection

---

## Important Notes

### Field Slugs and URLs
HivePress uses field values directly in filter URLs. Values with spaces can cause matching issues. Use underscores in your data values (e.g. `Land_Rover`) and the dropdown will display them cleanly. If needed, a display helper can be added to replace underscores with spaces in the visible label while keeping the value intact for filtering.

### Field Registration
The plugin registers parent and child fields as native HivePress listing attributes. This means they appear in:
- The listing edit form (admin and frontend)
- HivePress search and filter forms (if enabled)
- Listing cards and detail pages (based on display settings)
- HivePress's own attribute management

### No Conflicts with Multi-Step Form
This plugin is compatible with the **HivePress Multi-Step Form** plugin. Both can be active simultaneously. The conditional dropdown logic runs independently of the step navigation.

---

## File Structure

```
hpcd/
├── hp-conditional-dropdowns.php    # Main plugin file
├── assets/
│   ├── css/
│   │   ├── admin.css               # Admin panel styles
│   │   └── frontend.css            # Frontend styles
│   └── js/
│       ├── admin.js                # Admin data management
│       └── conditional.js          # Frontend conditional logic + display rewrite
└── includes/
    ├── class-admin.php             # Admin pages, pair management, data table
    ├── class-ajax.php              # AJAX handlers (add/delete rows, CSV import/export)
    ├── class-db.php                # Database schema and queries
    ├── class-fields.php            # HivePress attribute registration
    └── class-scripts.php           # Frontend asset enqueuing + inline data
```

---

## Database Tables

The plugin creates two custom tables on activation:

**`wp_hpcd_field_pairs`** — stores each parent/child pair and its settings (labels, icons, format, category restrictions, display options).

**`wp_hpcd_conditional_data`** — stores the parent→child value mappings (the actual dropdown data).

Both tables are removed cleanly on plugin uninstall.

---

## Changelog

### 2.0.2 (v18)
- Fixed filter page reload loop using capture-phase `stopImmediatePropagation()`
- Fixed icons not appearing on listing cards after AJAX filter
- Fixed MutationObserver in display format section not initializing when page already loaded
- Non-bubbling `dispatchEvent` for cascading child pairs

### 2.0.1 (v17)
- Added `isFilterForm()` detection to prevent `trigger('change')` on filter forms
- Added `_pauseObserver` flag to prevent MutationObserver firing during DOM rebuilds

### 2.0.0 (v16)
- Full rewrite with category restriction support
- Admin drag-and-drop data management
- Display format system with icon support (`%icon%`, `%label%`, `%value%` tokens)
- Client-side attribute display rewrite for listing cards and detail pages
- CSV import and export

### 1.x
- Initial versions based on CSV file for conditional data (location selector origin)


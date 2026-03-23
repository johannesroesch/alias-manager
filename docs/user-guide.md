# User Guide – Alias Manager

This guide is intended for editors and anyone who wants to manage aliases in the WordPress backend. No technical knowledge is required.

---

## What is an alias?

An alias is an alternative URL path that automatically redirects visitors to a different page. Example:

- Visitor opens `https://example.com/summer`
- The plugin immediately redirects them to `https://example.com/shop/offers/summer-2024`
- The redirect happens silently in the background (HTTP 301)

Aliases are useful for:
- Short, memorable URLs for campaigns or print materials
- Redirects after restructuring your site architecture
- Multiple entry points for a single page (e.g. `products` and `services` both pointing to the same page)

---

## Adding an alias

1. In the WordPress admin, go to **Settings → Alias Manager**.
2. Fill in the **"Add New Alias"** form:

### Alias Path

Enter only the slug — the part of the URL after the slash. Example:

- Desired URL: `https://example.com/summer-sale`
- Alias path: `summer-sale`

Allowed characters: letters, numbers, hyphens (`-`), underscores (`_`), slashes for multi-level paths (`shop/summer`).

### Select Page (optional)

Choose a published WordPress page from the dropdown. The Target URL field will be filled in automatically.

### Target URL

The full address the alias should redirect to. Must start with `http://` or `https://`. External URLs are also supported.

3. Click **"Add Alias"**.

On success, a green confirmation message appears and the new alias is immediately active.

---

## Editing an alias

1. In the **"Existing Aliases"** table, click **Edit** next to the alias you want to change.
2. The form opens pre-filled with the existing values.
3. Make your changes and click **"Update Alias"**.
4. Click **"Cancel"** to return to the list without saving changes.

> **Note:** If you change the alias path, the old path will no longer work. Make sure to inform anyone who has the old link.

---

## Deleting an alias

1. In the table, click **Delete** (shown in red) next to the alias.
2. A confirmation dialog appears: **"Really delete this alias?"**
3. Confirm with **OK**.

After deletion, the former alias path no longer redirects. Visitors who access the path will land on the WordPress 404 page.

---

## Overview table

The table in the "Existing Aliases" section shows all saved entries with the following columns:

| Column | Description |
|---|---|
| Alias Path | The slug including a preview of the full URL |
| Target URL | The redirect destination as a clickable link |
| Created | Date the alias was created |
| Actions | Edit and Delete links |

---

## Frequently asked questions

**Can an alias point to an external website?**
Yes. Simply enter the full external URL (e.g. `https://partner.example.com/offer`) in the Target URL field.

**Can I create multiple aliases for the same page?**
Yes, as many as you like. Each alias must have a unique path.

**What happens if an alias path matches an existing page slug?**
The alias path takes priority and the redirect fires before WordPress loads the page. Avoid conflicts with existing page slugs.

**How quickly is a new alias active?**
Immediately after saving — no cache clearing or additional steps required.

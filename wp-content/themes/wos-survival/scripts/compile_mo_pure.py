import struct
import os

def generate_mo(po_file_path, mo_file_path):
    print(f"Reading {po_file_path}...")
    try:
        with open(po_file_path, 'r', encoding='utf-8') as f:
            lines = f.readlines()
    except FileNotFoundError:
        print(f"Error: File {po_file_path} not found.")
        return

    messages = {}
    current_msgid = None
    current_msgstr = None
    
    # Very basic PO parser
    for line in lines:
        line = line.strip()
        if line.startswith('msgid "'):
            if current_msgid is not None and current_msgstr is not None:
                messages[current_msgid] = current_msgstr
            current_msgid = line[7:-1]
            current_msgstr = None
        elif line.startswith('msgstr "'):
            current_msgstr = line[8:-1]
    
    # Add last one
    if current_msgid is not None and current_msgstr is not None:
        messages[current_msgid] = current_msgstr

    # Remove empty header msgid (metadata) from actual translations if present as key ""
    # But usually needed for metadata? WP might use it. 
    # But for display strings, we don't need it. 
    # Let's keep it if it's there as it contains charset info etc.
    # Actually, for gettext, "" maps to the header items.
    
    # Sort keys
    keys = sorted(messages.keys())
    count = len(keys)
    
    # Offsets
    ids_offset = 28 # Header size: 4*7 = 28 bytes
    strs_offset = ids_offset + count * 8
    
    # Hash table size = 0, offset = 0 for simplicity.
    
    # Values buffer
    ids_buffer = b''
    strs_buffer = b''
    
    # Data buffer starts after tables
    data_offset = strs_offset + count * 8
    
    ids_data = b''
    strs_data = b''
    
    for key in keys:
        val = messages[key]
        
        # Original string
        key_bytes = key.encode('utf-8') + b'\0'
        ids_buffer += struct.pack('II', len(key_bytes) - 1, data_offset + len(ids_data) + len(strs_data))
        ids_data += key_bytes
        
    for key in keys:
        val = messages[key]
        
        # Translated string
        val_bytes = val.encode('utf-8') + b'\0'
        strs_buffer += struct.pack('II', len(val_bytes) - 1, data_offset + len(ids_data) + len(strs_data))
        strs_data += val_bytes

    # Header
    # magic 0x950412de
    # revision 0
    # count
    # offset of original strings
    # offset of translated strings
    # size of hash table (0)
    # offset of hash table (0)
    header = struct.pack('I', 0x950412de) # Magic
    header += struct.pack('I', 0)         # Revision
    header += struct.pack('I', count)     # Count
    header += struct.pack('I', ids_offset) # Offset Orig
    header += struct.pack('I', strs_offset) # Offset Trans
    header += struct.pack('I', 0)         # Hash Size
    header += struct.pack('I', 0)         # Hash Offset
    
    with open(mo_file_path, 'wb') as f:
        f.write(header)
        f.write(ids_buffer)
        f.write(strs_buffer)
        f.write(ids_data)
        f.write(strs_data)
        
    print(f"Successfully compiled to {mo_file_path}")

if __name__ == "__main__":
    generate_mo(
        'i:\\04_develop\\howasaba-lab\\wp-content\\themes\\wos-survival\\languages\\ja.po',
        'i:\\04_develop\\howasaba-lab\\wp-content\\themes\\wos-survival\\languages\\ja.mo'
    )

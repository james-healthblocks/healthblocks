from flask import Flask, request
from Savoir import Savoir
import flask
import json
import logging
import codecs
import ast
import time

app = Flask(__name__)

# change info for different blockchain
rpcuser = 'multichainrpc'
rpcpasswd = '6KTuy8TVYML915AobNkK7vRs6YNHzt79z2pQ8G6rLY5B'
rpchost = 'localhost'
rpcport = '5782'
chainname = 'healthnetwork'

# healthwallet_mapping - stream for mapping HealthWallets with UICs/HP ids
# healthportal_records - stream for storing records
# healthportal_access - stream for storing access requests

# initialize Savoir - wrapper for JSON-RPC API of multichain
api = Savoir(rpcuser, rpcpasswd, rpchost, rpcport, chainname)
# initialize logging for Savoir. This should log errors from Savoir (multichain) to stdout
logging.basicConfig()

# function to get wallet address from healthwallet_mapping stream based on key (UIC or HP id)
def get_wallet_address(key):
    # search wallet address based on key
    search_result = api.liststreamkeyitems('healthwallet_mapping', key)
    search_result = json.dumps(search_result)
    search_result = json.loads(search_result)
    if not search_result:
      return -1
    else:
      # stream data is stored as hex bytes
      # code below decodes hex bytes and converts to a list
      stream_data_hex = search_result[0]['data']
      stream_data = stream_data_hex.decode("hex");
      hp_data = ast.literal_eval(stream_data)
      return hp_data['wallet_address']

@app.route('/')
def hello_world():
    return 'Hello, World!'

@app.route('/add-stream', methods=['POST','GET'])
def add_stream():
    if request.method == 'POST':
      content = json.dumps(request.form)
      
      print content
    return flask.jsonify(**api.getinfo())

@app.route('/register/healthwallet', methods=['POST','GET'])
def add_healthwallet():
    if request.method == 'POST':
      if 'UIC' in request.form:
        uic = request.form['UIC'] 

        # generate new wallet address
        wallet_address = api.getnewaddress()

        # cross check with philhealth master data

        # grant send, receive, create access to new address so they can publish to stream
        api.grant(wallet_address,'send,receive,create')
        
        # convert data to hex for storage in stream
        stream_data = {'wallet_address':wallet_address, 'status':'active'}
        stream_data = json.dumps(stream_data)
        stream_data_hex = "".join("{:02x}".format(ord(c)) for c in stream_data)
        
        # publish to stream using new wallet address
        api.publishfrom(wallet_address, 'healthwallet_mapping', uic, stream_data_hex)

        content = {'status':'success', 'wallet_address':wallet_address}
      else:
        content = {'status':'failed'}
    else:
      content = {'status':'failed'}

    return flask.jsonify(**content)

@app.route('/register/healthportal', methods=['POST','GET'])
def add_healthportal():
    if request.method == 'POST':
      if 'HPid' in request.form:
        hpid = request.form['HPid'] 

        # generate new wallet address
        wallet_address = api.getnewaddress()

        # grant send, receive, create access to new address so they can publish to stream
        api.grant(wallet_address,'send,receive,create')
        
        # convert data to hex for storage in stream
        stream_data = {'wallet_address': wallet_address, 'status':'active'}
        stream_data = json.dumps(stream_data)
        stream_data_hex = "".join("{:02x}".format(ord(c)) for c in stream_data) 
        
        # publish to stream using new wallet address
        api.publishfrom(wallet_address, 'healthwallet_mapping', hpid, stream_data_hex)

        content = {'status':'success', 'wallet_address':wallet_address}
      else:
        content = {'status':'failed'}
    else:
      content = {'status':'failed'}

    return flask.jsonify(**content)

@app.route('/add/record', methods=['POST','GET'])
def add_record():
    if request.method == 'POST':
      if 'UIC' in request.form and 'HPid' in request.form and 'remarks' in request.form:
        hpid = request.form['HPid']
        remarks = request.form['remarks']
        uic = request.form['UIC']
        
        # search wallet address based on HP id
        wallet_address = get_wallet_address(hpid)
        if wallet_address is -1:
          content = {'status':'failed'}
          return flask.jsonify(**content)
        elif wallet_address != wallet_address_given:
          content = {'status':'failed'}
          return flask.jsonify(**content)

#        search_result = api.liststreamkeyitems('healthwallet_mapping', hpid)
#        search_result = json.dumps(search_result)
#        search_result = json.loads(search_result)
#        stream_data_hex = search_result[0]['data']
#        stream_data = stream_data_hex.decode("hex");
#        hp_data = ast.literal_eval(stream_data)

		# create data to be saved in stream and convert to json format
        to_stream_data = {'hpid':hpid,'remarks':remarks}
        to_stream_data = json.dumps(to_stream_data)
        to_stream_data_hex = "".join("{:02x}".format(ord(c)) for c in to_stream_data) 

        # publish to stream
        api.publishfrom(wallet_address, 'healthportal_records', uic, to_stream_data_hex)

        content = {'status':'success'}
      else:
        content = {'status':'failed'}
    else:
      content = {'status':'failed'}

    return flask.jsonify(**content)

@app.route('/search/record', methods=['POST','GET'])
def search_record():
    if request.method == 'POST':
      if 'UIC' in request.form:
        uic = request.form['UIC']
        # search wallet address based on HP id
        search_result = api.liststreamkeyitems('healthportal_records', uic)

        # convert search result to python list
        search_result = json.dumps(search_result)
        search_result = json.loads(search_result)

        # results to be returned by function
        result_list = []

        # iterate over search results from blockchain
        for item in search_result:
          # get relevant information from blockchain
          txn_datetime = item['blocktime']
          data = item['data'].decode("hex")
          data = ast.literal_eval(data)
          
          # check if these params exist. if not, use with empty string
          if 'hpid' in data:
            hpid = data['hpid']
          else:
            hpid = ""
          if 'remarks' in data:
            remarks = data['remarks']
          else:
            remarks = ""
            
          result_item = {'uic':uic,'hpid':hpid, 'txn_date': txn_datetime, 'remarks': remarks}
          result_list.append(result_item)

        #print result_list

        content = search_result
        return flask.jsonify(results=result_list)
        #content = {'status':'success'}
      else:
        content = {'status':'failed'}

    return flask.jsonify(**content)

@app.route('/add/request/hp', methods=['POST','GET'])
def add_hprequest():
    if request.method == 'POST':
      if 'UIC' in request.form and 'recv_hpid' in request.form and 'send_hpid' in request.form and 'recv_hpid_pubkey' in request.form:
        recv_hpid = request.form['recv_hpid']
        send_hpid = request.form['send_hpid']
        recv_hpid_pubkey = request.form['recv_hpid_pubkey']
        uic = request.form['UIC']

        # search wallet address based on HP id
        wallet_address = get_wallet_address(recv_hpid)
        if wallet_address is -1:
          content = {'status':'failed'}
          return flask.jsonify(**content)

		# create data to be saved in stream and convert to json format
        to_stream_data = {'recv_hpid':recv_hpid,'send_hpid':send_hpid,'recv_hpid_pubkey':recv_hpid_pubkey,'approve_status':'false'}
        to_stream_data = json.dumps(to_stream_data)
        to_stream_data_hex = "".join("{:02x}".format(ord(c)) for c in to_stream_data) 

        # publish to stream
        api.publishfrom(wallet_address, 'healthportal_access', uic, to_stream_data_hex)

        content = {'status':'success'}
      else:
        content = {'status':'failed'}

    return flask.jsonify(**content)

@app.route('/add/request/approve', methods=['POST','GET'])
def approve_hprequest():
    if request.method == 'POST':
      if 'UIC' in request.form and 'recv_hpid' in request.form and 'send_hpid' in request.form and 'recv_hpid_pubkey' in request.form and 'wallet_address' in request.form and 'approve_status' in request.form:
        recv_hpid = request.form['recv_hpid']
        send_hpid = request.form['send_hpid']
        recv_hpid_pubkey = request.form['recv_hpid_pubkey']
        uic = request.form['UIC']
        approve_status = request.form['approve_status']
        wallet_address_given = request.form['wallet_address']

        # search wallet address based on HP id
        wallet_address = get_wallet_address(uic)
        if wallet_address is -1:
          content = {'status':'failed'}
          return flask.jsonify(**content)
        # check if wallet address of UIC matches wallet address given in parameters
        elif wallet_address != wallet_address_given:
          content = {'status':'failed'}
          return flask.jsonify(**content)

		# create data to be saved in stream and convert to json format
        to_stream_data = {'recv_hpid':recv_hpid,'send_hpid':send_hpid,'recv_hpid_pubkey':recv_hpid_pubkey,'approve_status':approve_status}
        to_stream_data = json.dumps(to_stream_data)
        to_stream_data_hex = "".join("{:02x}".format(ord(c)) for c in to_stream_data) 

        # publish to stream
        api.publishfrom(wallet_address, 'healthportal_access', uic, to_stream_data_hex)

        #print stream_data
        #for key, value in search_result.items():
        #  print key, value
        content = {'status':'success'}
      else:
        content = {'status':'failed'}

    return flask.jsonify(**content)

@app.route('/search/request', methods=['POST','GET'])
def search_request():
    if request.method == 'POST':
      if 'UIC' in request.form:
        uic = request.form['UIC']
        # search wallet address based on HP id
        search_result = api.liststreamkeyitems('healthportal_access', uic, 0, 50, -1)

        # convert search result to python list
        search_result = json.dumps(search_result)
        search_result = json.loads(search_result)

        # results to be returned by function
        result_list = []

        # iterate over search results from blockchain
        for item in search_result:
          txn_datetime = item['blocktime']
          data = item['data'].decode("hex")
          data = ast.literal_eval(data)
          
          # check if these params exist. if not, use with empty string
          if 'recv_hpid' in data: 
            recv_hpid = data['recv_hpid']
          else:
            recv_hpid = ""
          if 'send_hpid' in data: 
            send_hpid = data['send_hpid']
          else:
            send_hpid = ""
          if 'recv_hpid_pubkey' in data: 
            recv_hpid_pubkey = data['recv_hpid_pubkey']
          else:
            recv_hpid_pubkey = ""
          if 'approve_status' in data:
            approve_status = data['approve_status']
          else:
            approve_status = ""

          result_item = {'uic':uic,'recv_hpid':recv_hpid,'send_hpid':send_hpid,'recv_hpid_pubkey':recv_hpid_pubkey,'txn_date': txn_datetime, 'approve_status':approve_status}

          # get only latest access request from recv_hpid
          if not any(d['recv_hpid'] == recv_hpid for d in result_list):
            # filter only specific hpid for results if requested via param 'send_hpid'
            if 'send_hpid' in request.form:
              send_hpid_param = request.form['send_hpid']
              if send_hpid == send_hpid_param and approve_status == 'true':
                result_item = {'recv_hpid_pubkey':recv_hpid_pubkey,'txn_date': txn_datetime, 'approve_status':approve_status}
                result_list.append(result_item)
            else: 
              result_list.append(result_item)

        # sort by most recent entry
        result_list = sorted(result_list, key=lambda x:x['txn_date'], reverse=True)

        content = search_result
        return flask.jsonify(results=result_list)
        #content = {'status':'success'}
      else:
        content = {'status':'failed'}
    else:
      content = {'status':'failed'}


    return flask.jsonify(**content)
